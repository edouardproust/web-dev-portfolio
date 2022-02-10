<?php

namespace App\Twig;

use App\Entity\Menu\Menu;
use Twig\TwigFilter;
use Twig\TwigFunction;
use Twig\Extension\AbstractExtension;

class AppExtension extends AbstractExtension
{
    public function getFunctions()
    {
        return [
            new TwigFunction('config', [$this, 'getConfigConstant']),
            new TwigFunction('menu', [$this, 'renderMenu']),
        ];
    }

    public function getFilters()
    {
        return [
            new TwigFilter('extract', [$this, 'getExtract']),
            new TwigFilter('safeEmail', [$this, 'getAntiScrappingEmailString']),
        ];
    }

    public function getConfigConstant(string $constant)
    {
        return constant('\App\Config::' . $constant);
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

    public function getAntiScrappingEmailString(string $email): string
    {
        $parts = explode("@", $email);
        $start = substr($parts[0], 0, 10);
        $end = explode(".", $parts[1])[1];
        return $start . '***@***.' . $end;
    }
}
