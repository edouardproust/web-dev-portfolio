<?php

namespace App\Controller\Admin;

use App\Entity\Comment;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;

class CommentCrudController extends AbstractEntityCrudController
{
    public static function getEntityFqcn(): string
    {
        return Comment::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural('Comments')
            ->setEntityLabelInSingular('Comment')
            ->setDefaultSort(['createdAt' => 'DESC'])
            ->setEntityPermission('ROLE_ADMIN');
    }

    public function setFields(): iterable
    {
        yield IdField::new('id')->onlyOnDetail();
        yield TextField::new('content');
        yield TextEditorField::new('description');
    }
}
