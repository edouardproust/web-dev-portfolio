<?php

namespace App\Twig;

use Twig\TwigFilter;
use Twig\TwigFunction;
use Twig\Extension\AbstractExtension;
use Symfony\Component\HttpFoundation\Request;

class AppExtension extends AbstractExtension
{
    public function getFunctions()
    {
        return [
            new TwigFunction('config', [$this, 'getConfigConstant']),
            new TwigFunction('eaConst', [$this, 'getEasyAdminConstant']),
            new TwigFunction('eaConst', [$this, 'getEasyAdminConstant']),
        ];
    }

    public function getFilters()
    {
        return [
            new TwigFilter('extract', [$this, 'getExtract']),
            new TwigFilter('safeEmail', [$this, 'getAntiScrappingEmailString']),
            new TwigFilter('int', [$this, 'convertToInteger']),
        ];
    }

    public function getConfigConstant(string $constant)
    {
        return constant('\App\Config::' . $constant);
    }

    /**
     * @var \EasyCorp\Bundle\EasyAdminBundle\Config\Option\EA EasyAdmin options reference
     *
     * @param Request $request
     * @param string $option
     * @param string $optionBag
     * @return null|string
     */
    public function getEasyAdminConstant(string $option, ?Request $request = null, ?string $constantBag = null): ?string
    {
        if ($constantBag === null) {
            $constant = constant('\EasyCorp\Bundle\EasyAdminBundle\Config\Option\EA::' . $option);
            return $request->query->get($constant);
        }
        return constant('\EasyCorp\Bundle\EasyAdminBundle\Config\\' . $constantBag . '::' . $option);
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

    public function getAntiScrappingEmailString(string $email, bool $enabled = true): string
    {
        if ($enabled) {
            $email = str_replace('@', '(at)', $email);
            $email = str_replace('.', '(dot)', $email);
        }
        return $email;
    }

    public function convertToInteger(string $str)
    {
        return (int)$str;
    }
}
