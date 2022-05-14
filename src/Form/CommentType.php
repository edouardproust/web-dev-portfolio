<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class CommentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('fullName', TextType::class, [
                'label' => "Your name",
                'attr' => [ 'placeholder' => 'Name', ],
                'row_attr' => [ 'class' => 'form-floating' ],
            ])
            ->add('content', TextareaType::class, [
                'label' => 'Your comment',
                'attr' => [ 'placeholder' => 'Content', ],
                'row_attr' => [ 'class' => 'form-floating' ],
            ]);
    }
}
