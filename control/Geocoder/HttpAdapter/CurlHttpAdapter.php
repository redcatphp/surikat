<?php

/**
 * This file is part of the Geocoder package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license    MIT License
 */

namespace surikat\control\Geocoder\HttpAdapter;

use surikat\control\Geocoder\Exception\ExtensionNotLoadedException;

/**
 * @author William Durand <william.durand1@gmail.com>
 */
class CurlHttpAdapter implements HttpAdapterInterface
{
    private $timeout;

    private $connectTimeout;

    public function __construct($timeout = null, $connectTimeout = null)
    {
        $this->timeout = $timeout;
        $this->connectTimeout = $connectTimeout;
    }

    /**
     * {@inheritDoc}
     */
    public function getContent($url)
    {
        if (!function_exists('curl_init')) {
            throw new ExtensionNotLoadedException('cURL has to be enabled.');
        }

        $c = curl_init();
        curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($c, CURLOPT_URL, $url);

        if ($this->timeout) {
            curl_setopt($c, CURLOPT_TIMEOUT, $this->timeout);
        }

        if ($this->connectTimeout) {
            curl_setopt($c, CURLOPT_CONNECTTIMEOUT, $this->connectTimeout);
        }

        $content = curl_exec($c);
        curl_close($c);

        if (false === $content) {
            $content = null;
        }

        return $content;
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'curl';
    }
}
