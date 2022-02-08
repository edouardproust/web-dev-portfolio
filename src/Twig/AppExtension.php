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
            new TwigFilter('safeEmail', [$this, 'getAntiScrappingEmailString']),
        ];
    }

    public function getExtract(
        string $content,
        int $maxCharacters = 100,
        $replacer = '...'
    ): string {
        if (strlen($content) > $maxCharacters) {
            return (preg_match('/^(.*)\W.*$/', substr($content, 0, $maxCharacters + 1), $matches)
                    ? $matches[1]
                    : substr($content, 0, $maxCharacters)) . $replacer;
        }
        return $content;
    }

    public function getAntiScrappingEmailString(string $email)
    {
        $parts = explode("@", $email);
        $start = substr($parts[0], 0, 10);
        $end = explode(".", $parts[1])[1];
        return $start . '***@***.' . $end;
    }
}
