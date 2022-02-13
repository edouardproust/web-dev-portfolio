<?php

namespace App\Controller\Admin;

use App\Entity\CodingLanguage;

class CodingLanguageCrudController extends AbstractCategoryCrudController
{
    public static function getEntityFqcn(): string
    {
        return CodingLanguage::class;
    }

}
