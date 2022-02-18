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
}
