<?php

namespace App\Helper;

class StringHelper
{
    public static function extract(
        string $content,
        int $maxCharacters = 100,
        $replacer = '...'
    ) {
        if (strlen($content) > $maxCharacters) {
            return (preg_match('/^(.*)\W.*$/', substr($content, 0, $maxCharacters + 1), $matches)
                ? $matches[1]
                : substr($content, 0, $maxCharacters)) . $replacer;
        }
        return $content;
    }

    /**
     * Convert a string value into a boolean value (true or false)
     * @param string $str The string to convert
     * @return bool 
     */
    public static function toBool(string $str)
    {
        if (in_array($str, ['true', '1'])) {
            return true;
        } else {
            return false;
        }
    }

    public static function toString($value)
    {
        if (is_bool($value)) {
            if ($value) return '1';
            return '0';
        }
    }
}
