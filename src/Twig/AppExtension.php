<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('extract', [$this, 'getExtract']),
        ];
    }

    public function getExtract(
        string $content,
        int $maxCharacters = 100,
        $replacer = '...'
    ): string
    {
        if(strlen($content) > $maxCharacters)
        return 
            (preg_match('/^(.*)\W.*$/', substr($content, 0, $maxCharacters+1), $matches)
            ? $matches[1]
            : substr($content, 0, $maxCharacters)) . $replacer;
        return $content;
    }
}