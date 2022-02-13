<?php

namespace App\Controller\Admin;

use App\Entity\ProjectCategory;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;

class ProjectCategoryCrudController extends AbstractCategoryCrudController
{
    public static function getEntityFqcn(): string
    {
        return ProjectCategory::class;
    }

    public function setAdditionalFields(): array
    {
        return [
            TextareaField::new('description')
                ->setSortable(false)
                ->hideOnIndex()
                ->setCustomOption('position', 3)
        ];
    }
}
