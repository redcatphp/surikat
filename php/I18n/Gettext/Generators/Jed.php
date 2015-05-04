<?php
namespace I18n\Gettext\Generators;

use I18n\Gettext\Translations;

class Jed extends Generator implements GeneratorInterface
{
    /**
     * {@parentDoc}
     */
    public static function toString(Translations $translations)
    {
        $array = PhpArray::toArray($translations);

        return json_encode($array);
    }
}