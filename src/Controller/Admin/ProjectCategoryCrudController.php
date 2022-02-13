<?php

namespace App\Controller\Admin;

use App\Entity\ProjectCategory;

class ProjectCategoryCrudController extends AbstractCategoryCrudController
{
    public static function getEntityFqcn(): string
    {
        return ProjectCategory::class;
    }
}
