<?php

namespace App\Controller\Admin;

use App\Entity\Post;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class PostCrudController extends AbstractPosttypeCrudController
{
    protected $route = 'post';

    public static function getEntityFqcn(): string
    {
        return Post::class;
    }

    public function setFields(): array
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextareaField::new('headline')
        ];
    }
}
