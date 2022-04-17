<?php

namespace App\Twig;

use App\Entity\User;
use Twig\TwigFilter;
use App\Entity\Author;
use Twig\TwigFunction;
use App\Helper\FileHelper;
use App\Helper\StringHelper;
use App\Service\EasyAdminService;
use App\Service\AdminOptionService;
use Twig\Extension\AbstractExtension;
use Symfony\Component\HttpFoundation\Request;

class AppExtension extends AbstractExtension
{
    private $adminOptionService;
    private $easyAdminService;

    public function __construct(
        AdminOptionService $adminOptionService,
        EasyAdminService $easyAdminService
    ) {
        $this->adminOptionService = $adminOptionService;
        $this->easyAdminService = $easyAdminService;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('config', [$this, 'getAdminOptionValue']),
            new TwigFunction('appConfig', [$this, 'getAppConfig']),
            new TwigFunction('uploadUrl', [$this, 'getUploadUrlFromPublicDir']),
            new TwigFunction('eaConst', [$this, 'getEasyAdminConstant']),
            new TwigFunction('eaAuthorName', [$this, 'getEasyAdminAuthorFullname']),
            new TwigFunction('isAdminAccessGranted', [$this, 'isAdminPanelAccessGranted']),
            new TwigFunction('file', [$this, 'callFileHelper']),
            new TwigFunction('slideType', [$this, 'getSlideType']),
            new TwigFunction('fileContent', [$this, 'fileGetContents'])
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

    /**
     * Get constant value from AdminOptions
     * @param string $constant 
     * @return string 
     */
    public function getAdminOptionValue(string $constant)
    {
        return $this->adminOptionService->get($constant);
    }

    /**
     * Get constant value from src\Config.php
     * @param string $constant 
     * @return void 
     */
    public function getAppConfig(string $constant)
    {
        return constant('App\\Config::' . $constant);
    }

    public function getUploadUrlFromPublicDir(?string $pathConstant, ?string $fileName): ?string
    {
        if (!$pathConstant || !$fileName) {
            return null;
        }
        return constant('\App\Path' . '::' . $pathConstant) . '/' . $fileName;
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

    public function isAdminPanelAccessGranted(): bool
    {
        return $this->easyAdminService->isAdminPanelAccessGranted();
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

    public function callFileHelper(string $fnOrConst, ...$arguments)
    {
        // get a constant value 
        if (in_array($fnOrConst, array_keys(FileHelper::getConstants()))) {
            return FileHelper::getConstant($fnOrConst);
        }
        // run a function
        return FileHelper::$fnOrConst(...$arguments);
    }

    public function getSlideType(string $file): ?string
    {
        foreach (FileHelper::getUplodadTypes() as $type) {
            if (FileHelper::getMime($file) === $type) {
                return $type;
            }
        }
    }

    public function fileGetContents($pathConstant, $fileName)
    {
        return file_get_contents(FileHelper::getAbsPath($pathConstant, $fileName));
    }
}
