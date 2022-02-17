<?php

namespace App\Controller\Admin;

use App\Config;
use App\Entity\Comment;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Dto\BatchActionDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;

class CommentCrudController extends AbstractEntityCrudController
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

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

        yield FormField::addPanel()->setCssClass(Config::ADMIN_FORM_MAIN_CSS_CLASS);
        yield TextField::new('fullName')->setLabel('Author name');
        yield TextField::new('extract')
            ->onlyOnIndex()
            ->setLabel('Comment');
        yield TextareaField::new('content')->hideOnIndex();

        yield FormField::addPanel()->setCssClass(Config::ADMIN_FORM_SIDE_CSS_CLASS);
        yield BooleanField::new('isVisible')->hideWhenCreating();
        yield AssociationField::new('project')
            ->hideOnIndex()
            ->setLabel('On project');
        yield AssociationField::new('lesson')
            ->hideOnIndex()
            ->setLabel('On lesson');
        yield AssociationField::new('post')
            ->hideOnIndex()
            ->setLabel('On post');
        yield DateTimeField::new('createdAt')
            ->hideWhenCreating()
            ->setLabel('Posted on')
            ->setFormat('medium');
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->remove(Crud::PAGE_EDIT, Action::SAVE_AND_CONTINUE)
            ->addBatchAction(
                Action::new('bulkHide', 'Hide')
                    ->linkToCrudAction('hideComments')
                    ->addCssClass('btn btn-primary')
                    ->setIcon('fas fa-eye-slash')
            )
            ->addBatchAction(
                Action::new('bulkShow', 'Make visible')
                    ->linkToCrudAction('makeCommentsVisible')
                    ->addCssClass('btn btn-primary')
                    ->setIcon('fas fa-eye')
            );
    }

    public function makeCommentsVisible(BatchActionDto $batchActionDto)
    {
        $this->setBulkCommentsVisibily(true, $batchActionDto);
        return $this->redirect($batchActionDto->getReferrerUrl());
    }

    public function hideComments(BatchActionDto $batchActionDto)
    {
        $this->setBulkCommentsVisibily(false, $batchActionDto);
        return $this->redirect($batchActionDto->getReferrerUrl());
    }

    private function setBulkCommentsVisibily(bool $makeVisible, BatchActionDto $batchActionDto)
    {
        foreach ($batchActionDto->getEntityIds() as $id) {
            /** @var Comment $comment */
            $comment = $this->entityManager->find(Comment::class, $id);
            $comment->setIsVisible($makeVisible);
            $this->entityManager->persist($comment);
        }
        $this->entityManager->flush();
    }
}
