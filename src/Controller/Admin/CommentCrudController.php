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
            ->setEntityPermission(Config::ROLE_ADMIN);
    }

    public function setFields(): iterable
    {
        yield IdField::new('id')->onlyOnDetail();

        yield FormField::addPanel()->setCssClass(Config::ADMIN_FORM_MAIN_CSS_CLASS);
        yield TextField::new('fullName')->setLabel('Author name');
        yield TextField::new('extract', 'Comment')->onlyOnIndex();
        yield TextareaField::new('content')->hideOnIndex();

        yield FormField::addPanel()->setCssClass(Config::ADMIN_FORM_SIDE_CSS_CLASS);
        yield BooleanField::new('isVisible', 'Visible')->hideWhenCreating();
        yield AssociationField::new('project', 'On project')->hideOnIndex();
        yield AssociationField::new('lesson', 'On lesson')->hideOnIndex();
        yield AssociationField::new('post', 'On post')->hideOnIndex();
        yield DateTimeField::new('createdAt', 'Posted on')
            ->hideWhenCreating()
            ->setFormat('medium');
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->remove(Crud::PAGE_EDIT, Action::SAVE_AND_CONTINUE)
            ->add(Crud::PAGE_EDIT, Action::DELETE)
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
            )
            ->reorder(Crud::PAGE_EDIT, [Action::SAVE_AND_RETURN, Action::DELETE]);
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
