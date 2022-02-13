<?php

namespace App\Controller\Admin;

use App\Entity\Lesson;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;

class LessonCrudController extends AbstractPosttypeCrudController
{

    protected $route = 'lesson';

    public static function getEntityFqcn(): string
    {
        return Lesson::class;
    }

    public function setFields(): array
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextareaField::new('headline'),
        ];
    }
}
