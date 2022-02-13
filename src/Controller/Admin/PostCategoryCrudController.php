<?php

namespace App\Controller\Admin;

use App\Entity\PostCategory;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;

class PostCategoryCrudController extends AbstractCategoryCrudController
{
    public static function getEntityFqcn(): string
    {
        return PostCategory::class;
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
