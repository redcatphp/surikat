<?php

/*
 * fusion of
 * Pimple 3 - 2009 Fabien Potencier
 * and Dice 1.4 - 2012-2015 Tom Butler <tom@r.je> | http://r.je/dice.html
 */

namespace Unit;

class DiContainer implements \ArrayAccess
{
    private $values = [];
    private $factories;
    private $protected;
    private $frozen = [];
    private $raw = [];
    private $keys = [];
    
    private $rules = [];
	private $cache = [];
	private $instances = [];
    
    public function __construct(array $values = [])
    {
        $this->factories = new \SplObjectStorage();
        $this->protected = new \SplObjectStorage();

        foreach ($values as $key => $value) {
            $this->offsetSet($key, $value);
        }
    }

    public function offsetSet($id, $value)
    {
        if (isset($this->frozen[$id])) {
            throw new \RuntimeException(sprintf('Cannot override frozen service "%s".', $id));
        }

        $this->values[$id] = $value;
        $this->keys[$id] = true;
    }

    public function offsetGet($id)
    {
        if (!isset($this->keys[$id])) {
            throw new \InvalidArgumentException(sprintf('Identifier "%s" is not defined.', $id));
        }

        if (
            isset($this->raw[$id])
            || !is_object($this->values[$id])
            || isset($this->protected[$this->values[$id]])
            || !method_exists($this->values[$id], '__invoke')
        ) {
            return $this->values[$id];
        }

        if (isset($this->factories[$this->values[$id]])) {
            return $this->values[$id]($this);
        }

        $raw = $this->values[$id];
        $val = $this->values[$id] = $raw($this);
        $this->raw[$id] = $raw;

        $this->frozen[$id] = true;

        return $val;
    }

    public function offsetExists($id)
    {
        return isset($this->keys[$id]);
    }

    public function offsetUnset($id)
    {
        if (isset($this->keys[$id])) {
            if (is_object($this->values[$id])) {
                unset($this->factories[$this->values[$id]], $this->protected[$this->values[$id]]);
            }

            unset($this->values[$id], $this->frozen[$id], $this->raw[$id], $this->keys[$id]);
        }
    }

    public function factory($callable)
    {
        if (!is_object($callable) || !method_exists($callable, '__invoke')) {
            throw new \InvalidArgumentException('Service definition is not a Closure or invokable object.');
        }

        $this->factories->attach($callable);

        return $callable;
    }

    public function protect($callable)
    {
        if (!is_object($callable) || !method_exists($callable, '__invoke')) {
            throw new \InvalidArgumentException('Callable is not a Closure or invokable object.');
        }

        $this->protected->attach($callable);

        return $callable;
    }

    public function raw($id)
    {
        if (!isset($this->keys[$id])) {
            throw new \InvalidArgumentException(sprintf('Identifier "%s" is not defined.', $id));
        }

        if (isset($this->raw[$id])) {
            return $this->raw[$id];
        }

        return $this->values[$id];
    }

    public function extend($id, $callable)
    {
        if (!isset($this->keys[$id])) {
            throw new \InvalidArgumentException(sprintf('Identifier "%s" is not defined.', $id));
        }

        if (!is_object($this->values[$id]) || !method_exists($this->values[$id], '__invoke')) {
            throw new \InvalidArgumentException(sprintf('Identifier "%s" does not contain an object definition.', $id));
        }

        if (!is_object($callable) || !method_exists($callable, '__invoke')) {
            throw new \InvalidArgumentException('Extension service definition is not a Closure or invokable object.');
        }

        $factory = $this->values[$id];

        $extended = function ($c) use ($callable, $factory) {
            return $callable($factory($c), $c);
        };

        if (isset($this->factories[$factory])) {
            $this->factories->detach($factory);
            $this->factories->attach($extended);
        }

        return $this[$id] = $extended;
    }

    public function keys()
    {
        return array_keys($this->values);
    }

    public function register($provider, array $values = [])
    {
        $provider->register($this);

        foreach ($values as $key => $value) {
            $this[$key] = $value;
        }

        return $this;
    }
    
    
    
    public function addRule($name, DiRule $rule) {
		$this->rules[ltrim(strtolower($name), '\\')] = $rule;
	}

	public function getRule($name) {
		if (isset($this->rules[strtolower(ltrim($name, '\\'))])) return $this->rules[strtolower(ltrim($name, '\\'))];
		foreach ($this->rules as $key => $rule) {
			if ($rule->instanceOf === null && $key !== '*' && is_subclass_of($name, $key) && $rule->inherit === true) return $rule;
		}
		return isset($this->rules['*']) ? $this->rules['*'] : new DiRule;
	}

	public function create($component, array $args = [], $forceNewInstance = false, $share = []) {
		if (!$forceNewInstance && isset($this->instances[$component])) return $this->instances[$component];
		if (empty($this->cache[$component])) {
			$rule = $this->getRule($component);
			$class = new \ReflectionClass($rule->instanceOf ?: $component);
			$constructor = $class->getConstructor();
			$params = $constructor ? $this->getParams($constructor, $rule) : null;

			$this->cache[$component] = function($args, $share) use ($component, $rule, $class, $constructor, $params) {
				if ($rule->shared) {
					$this->instances[$component] = $object = $class->newInstanceWithoutConstructor();
					if ($constructor) $constructor->invokeArgs($object, $params($args, $share));
				}
				else $object = $params ? new $class->name(...$params($args, $share)) : new $class->name;
				if ($rule->call) foreach ($rule->call as $call) $class->getMethod($call[0])->invokeArgs($object, call_user_func($this->getParams($class->getMethod($call[0]), $rule), $this->expand($call[1])));
				return $object;
			};
		}
		return $this->cache[$component]($args, $share);
	}

	private function expand($param, array $share = []) {
		if (is_array($param)) foreach ($param as &$key) $key = $this->expand($key, $share); 
		else if ($param instanceof DiInstance) return is_callable($param->name) ? call_user_func($param->name, $this, $share) : $this->create($param->name, [], false, $share);
		return $param;
	}

	private function getParams(\ReflectionMethod $method, DiRule $rule) {
		$paramInfo = [];
		foreach ($method->getParameters() as $param) {
			$class = $param->getClass() ? $param->getClass()->name : null;
			$paramInfo[] = [$class, $param->allowsNull(), array_key_exists($class, $rule->substitutions), in_array($class, $rule->newInstances)];
		}
		return function($args, $share = []) use ($paramInfo, $rule) {
			if ($rule->shareInstances) $share = array_merge($share, array_map([$this, 'create'], $rule->shareInstances));
			if ($share || $rule->constructParams) $args = array_merge($args, $this->expand($rule->constructParams, $share), $share);
			$parameters = [];

			foreach ($paramInfo as list($class, $allowsNull, $sub, $new)) {
				if ($args && $count = count($args)) for ($i = 0; $i < $count; $i++) {
					if ($class && $args[$i] instanceof $class || ($args[$i] === null && $allowsNull)) {
						$parameters[] = array_splice($args, $i, 1)[0];
						continue 2;
					}
				}
				if ($class) $parameters[] = $sub ? $this->expand($rule->substitutions[$class], $share) : $this->create($class, [], $new, $share);
				else if ($args) $parameters[] = $this->expand(array_shift($args));
			}
			return $parameters;
		};
	}
}