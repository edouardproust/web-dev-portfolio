<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'constraints' => [
                    new Email(),
                    new NotBlank()
                ],
                'attr' => [ 'placeholder' => 'Name', ],
                'row_attr' => [ 'class' => 'form-floating' ],
            ])
            ->add('password', PasswordType::class, [
                'required' => false,
                'label' => 'New password',
                'constraints' => [
                    new NotBlank()
                ],
                'attr' => [ 'placeholder' => 'Name', ],
                'row_attr' => [ 'class' => 'form-floating' ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
