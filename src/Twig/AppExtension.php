<?php

namespace App\Twig;

use App\Entity\User;
use Twig\TwigFilter;
use Twig\TwigFunction;
use App\Entity\Author;
use App\Helper\StringHelper;
use Twig\Extension\AbstractExtension;
use Symfony\Component\HttpFoundation\Request;

class AppExtension extends AbstractExtension
{
    public function getFunctions()
    {
        return [
            new TwigFunction('config', [$this, 'getConfigConstant']),
            new TwigFunction('eaConst', [$this, 'getEasyAdminConstant']),
            new TwigFunction('eaAuthorName', [$this, 'getEasyAdminAuthorFullname'])
        ];
    }

    public function getFilters()
    {
        return [
            new TwigFilter('extract', [$this, 'getExtract']),
            new TwigFilter('safeEmail', [$this, 'getAntiScrappingEmailString']),
            new TwigFilter('int', [$this, 'convertToInteger']),
            new TwigFilter('higherRole', [$this, 'getHigherRoleOfUser']),
            new TwigFilter('hasVisibleComments', [$this, 'hasVisibleComments']),
            new TwigFilter('hasApprovedAuthor', [$this, 'hasApprovedAuthor'])
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
        return StringHelper::extract($content, $maxCharacters, $replacer);
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

    public function getHigherRoleOfUser(?User $user, bool $capitalizeOutput = false): ?string
    {
        if (!$user) {
            return null;
        }
        $roles = $user->getRoles();
        $role = null;
        if (in_array('ROLE_ADMIN', $roles)) {
            $role = 'admin';
        } elseif (in_array('ROLE_AUTHOR', $roles)) {
            $role = 'author';
        }
        if ($role && $capitalizeOutput) {
            $role = ucfirst($role);
        }
        return $role;
    }

    public function getEasyAdminAuthorFullname(?Author $author, string $defaultName = 'Admin'): string
    {
        if ($author) {
            return $author->getFullName();
        } else {
            return $defaultName;
        }
    }

    public function hasVisibleComments(object $postType)
    {
        $hasVisibleComments = false;
        foreach ($postType->getComments() as $comment) {
            if ($comment->getIsVisible()) {
                $hasVisibleComments = true;
            }
        }
        return $hasVisibleComments;
    }

    public function hasApprovedAuthor(object $postType)
    {
        $author = $postType->getAuthor();
        if ($author && $author->getIsApproved()) {
            return true;
        }
        return false;
    }
}
