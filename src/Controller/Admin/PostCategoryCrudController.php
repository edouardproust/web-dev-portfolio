<?php

namespace App\Controller\Admin;

use App\Entity\PostCategory;

class PostCategoryCrudController extends AbstractCategoryCrudController
{
    public static function getEntityFqcn(): string
    {
        return PostCategory::class;
    }
}
