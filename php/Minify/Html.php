<?php namespace Minify;
use DependencyInjection\MutatorFacadeTrait;
#modified by surikat
/*
 Compress HTML
 Class HTML  
 @package Minify
 This is a heavy regex-based removal of whitespace, unnecessary comments and 
 tokens. IE conditional comments are preserved. There are also options to have
 STYLE and SCRIPT blocks compressed by callback functions. 
 @package Minify
 @author Stephen Clay <steve@mrclay.org>
*/
class Html {
	use MutatorFacadeTrait;
	protected $_isXhtml = null;
    protected $_replacementHash = null;
    protected $_placeholders = [];
	protected $_html;
    function __construct(){}
    function _process($html){
        $this->_html = str_replace("\r\n", "\n", trim($html));
        if ($this->_isXhtml === null)
            $this->_isXhtml = (false !== strpos($this->_html, '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML'));
        $this->_replacementHash = 'MINIFYHTML' . md5($_SERVER['REQUEST_TIME']);
        $this->_placeholders = [];
        // replace SCRIPTs (and minify) with placeholders
        $this->_html = preg_replace_callback(
            '/(\\s*)(<script\\b[^>]*?>)([\\s\\S]*?)<\\/script>(\\s*)/i'
            ,[$this, '_removeScriptCB']
            ,$this->_html);
        // replace STYLEs (and minify) with placeholders
        $this->_html = preg_replace_callback(
            '/\\s*(<style\\b[^>]*?>)([\\s\\S]*?)<\\/style>\\s*/i'
            ,[$this, '_removeStyleCB']
            ,$this->_html);
        // remove HTML comments (not containing IE conditional comments).
        $this->_html = preg_replace_callback(
            '/<!--([\\s\\S]*?)-->/'
            ,[$this, '_commentCB']
            ,$this->_html);
        // replace PREs with placeholders
        $this->_html = preg_replace_callback('/\\s*(<pre\\b[^>]*?>[\\s\\S]*?<\\/pre>)\\s*/i'
            ,[$this, '_removePreCB']
            ,$this->_html);
        // replace TEXTAREAs with placeholders
        $this->_html = preg_replace_callback(
            '/\\s*(<textarea\\b[^>]*?>[\\s\\S]*?<\\/textarea>)\\s*/i'
            ,[$this, '_removeTextareaCB']
            ,$this->_html);
        // trim each line.
        // @todo take into account attribute values that span multiple lines.
        $this->_html = preg_replace('/^\\s+|\\s+$/m', '', $this->_html);
        // remove ws around block/undisplayed elements
        $this->_html = preg_replace('/\\s+(<\\/?(?:area|base(?:font)?|blockquote|body'
            .'|caption|center|cite|col(?:group)?|dd|dir|div|dl|dt|fieldset|form'
            .'|frame(?:set)?|h[1-6]|head|hr|html|legend|li|link|map|menu|meta'
            .'|ol|opt(?:group|ion)|p|param|t(?:able|body|head|d|h||r|foot|itle)'
            .'|ul)\\b[^>]*>)/i', '$1', $this->_html);
        // remove ws outside of all elements
        $this->_html = preg_replace_callback(
            '/>([^<]+)</'
            ,[$this, '_outsideTagCB']
            ,$this->_html);
        // use newlines before 1st attribute in open tags (to limit line lengths)
        $this->_html = preg_replace('/(<[a-z\\-]+)\\s+([^>]+>)/i', "$1\n$2", $this->_html);
        // fill placeholders
        $this->_html = str_replace(
            array_keys($this->_placeholders)
            ,array_values($this->_placeholders)
            ,$this->_html
        );
        return str_replace("\n",' ',$this->_html);
    }
    protected function _commentCB($m){
        return (0 === strpos($m[1], '[') || false !== strpos($m[1], '<!['))? $m[0]: '';
    }
    protected function _reservePlace($content){
        $placeholder = '%' . $this->_replacementHash . count($this->_placeholders) . '%';
        $this->_placeholders[$placeholder] = $content;
        return $placeholder;
    }
    protected function _outsideTagCB($m){
        return '>' . preg_replace('/^\\s+|\\s+$/', ' ', $m[1]) . '<';
    }
    protected function _removePreCB($m){
        return $this->_reservePlace($m[1]);
    }
    protected function _removeTextareaCB($m){
        return $this->_reservePlace($m[1]);
    }
    protected function _removeStyleCB($m){
        $openStyle = $m[1];
        $css = $m[2];
        // remove HTML comments
        $css = preg_replace('/(?:^\\s*<!--|-->\\s*$)/', '', $css);
        // remove CDATA section markers
        $css = $this->_removeCdata($css);
        // minify
        $minifier = $this->__Css
            ? [$this->__Css,'process']
            : 'trim';
        $css = call_user_func($minifier, $css);
        return $this->_reservePlace($this->_needsCdata($css)
            ? "{$openStyle}/*<![CDATA[*/{$css}/*]]>*/</style>"
            : "{$openStyle}{$css}</style>"
        );
    }
    protected function _removeScriptCB($m){
        $openScript = $m[2];
        $js = $m[3];
        // whitespace surrounding? preserve at least one space
        $ws1 = ($m[1] === '') ? '' : ' ';
        $ws2 = ($m[4] === '') ? '' : ' ';
        // remove HTML comments (and ending "//" if present)
        $js = preg_replace('/(?:^\\s*<!--\\s*|\\s*(?:\\/\\/)?\\s*-->\\s*$)/', '', $js);
        // remove CDATA section markers
        $js = $this->_removeCdata($js);
        // minify
        $minifier = $this->__Js
            ? [$this->__Js,'process']
            : 'trim'; 
        $js = call_user_func($minifier, $js);
        return $this->_reservePlace($this->_needsCdata($js)
            ? "{$ws1}{$openScript}/*<![CDATA[*/{$js}/*]]>*/</script>{$ws2}"
            : "{$ws1}{$openScript}{$js}</script>{$ws2}"
        );
    }
    protected function _removeCdata($str){
        return (false !== strpos($str, '<![CDATA['))? str_replace(['<![CDATA[', ']]>'], '', $str): $str;
    }
    protected function _needsCdata($str){
        return ($this->_isXhtml && preg_match('/(?:[<&]|\\-\\-|\\]\\]>)/', $str));
    }
}