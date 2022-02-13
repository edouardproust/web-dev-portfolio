<?php

namespace App\Controller\Admin;

use App\Entity\LessonCategory;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;

class LessonCategoryCrudController extends AbstractCategoryCrudController
{
    public static function getEntityFqcn(): string
    {
        return LessonCategory::class;
    }

    public function setAdditionalFields(): array
    {
        return [
            TextareaField::new('description')
                ->setSortable(false)
        ];
    }
}
