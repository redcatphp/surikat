<?php namespace Surikat\Component\Minify;
use Surikat\Component\DependencyInjection\FacadeTrait;
class Css{
	use FacadeTrait;
	function _process($str){
		return str_replace(["\r\n", "\r", "\n", "\t", '  ', '    ', '    ',"\ r \ n", "\ r", "\ n", "\ t"],'',preg_replace( '! / \ *[^*]* \ *+([^/][^*]* \ *+)*/!','',preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!','',$str)));
	}
}