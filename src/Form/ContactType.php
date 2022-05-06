<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('fullName', TextType::class, [
                'label' => "Your name",
                'attr' => [ 'placeholder' => 'Name', ],
                'row_attr' => [ 'class' => 'form-floating' ],
            ])
            ->add('email', EmailType::class, [
                'attr' => [ 'placeholder' => 'Name', ],
                'row_attr' => [ 'class' => 'form-floating' ],
            ])
            ->add('subject', TextType::class, [
                'attr' => [ 'placeholder' => 'Name', ],
                'row_attr' => [ 'class' => 'form-floating' ],
            ])
            ->add('content', TextareaType::class, [
                'label' => 'Your message',
                'attr' => [ 'placeholder' => 'Name', 'style' => "height: 150px"],
                'row_attr' => [ 'class' => 'form-floating' ],
            ]);
    }

    public function getBlockPrefix(): string
    {
        return '';
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
