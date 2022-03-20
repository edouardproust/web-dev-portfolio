<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Url;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints\NotBlank;

class AuthorRegisterType extends AbstractType
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        if (!$this->security->getUser()) { // hide these fields if is a connected user
            $builder
                ->add('email', EmailType::class, [
                    'label' => 'Email*',
                    'constraints' => [
                        new NotBlank(),
                        new Email,
                        new Length(['max' => 255])
                    ],
                    'mapped' => false,
                ])
                ->add('password', PasswordType::class, [
                    'label' => 'Password*',
                    'constraints' => [
                        new NotBlank(),
                        new Length(['max' => 255])
                    ],
                    'mapped' => false
                ]);
        }
        $builder
            ->add('fullName', TextType::class, [
                'label' => 'Your name*',
                'constraints' => [
                    new NotBlank(),
                    new Length(['max' => 255])
                ],
            ])
            ->add('bio', TextareaType::class, [
                'label' => 'Bio*',
                'help' => 'Describe yourself a bit (50 to 500 characters).',
                'constraints' => [
                    new NotBlank(),
                    new Length([
                        'min' => 50,
                        'max' => 500
                    ])
                ],
            ])
            ->add('avatarFile', VichImageType::class, [
                'required' => false,
                'label' => 'Avatar',
                'help' => 'Your favorite photo of yourself! (jpeg or png, 500Ko max)',
                'constraints' => [
                    new File([
                        'maxSize' => '500k',
                        'mimeTypes' => ['image/jpeg', 'image/png', 'image/webp'],
                        'mimeTypesMessage' => 'Image format {{ type }} is not allowed. Please use one of the following: {{ types }}.'
                    ])
                ]
            ])
            ->add('contactEmail', EmailType::class, [
                'required' => false,
                'label' => 'Contact email',
                'help' => 'An address where readers of your posts can write to you.',
                'constraints' => [
                    new Email,
                    new Length(['max' => 255])
                ],
            ])
            ->add('website', UrlType::class, [
                'required' => false,
                'label' => 'Your website',
                'constraints' => [
                    new Url,
                    new Length(['max' => 255])
                ],
            ])
            ->add('github', UrlType::class, [
                'required' => false,
                'label' => 'GitHub',
                'help' => 'A link to you GiHub profile',
                'constraints' => [
                    new Url,
                    new Length(['max' => 255])
                ],
            ])
            ->add('linkedin', UrlType::class, [
                'required' => false,
                'label' => 'LinkedIn',
                'help' => 'A link to you LinkedIn profile',
                'constraints' => [
                    new Url,
                    new Length(['max' => 255])
                ],
            ])
            ->add('stackoverflow', UrlType::class, [
                'required' => false,
                'label' => 'StackOverflow',
                'help' => 'A link to you StackOverflow profile',
                'constraints' => [
                    new Url,
                    new Length(['max' => 255])
                ],
            ])
            ->add('submitRegister', SubmitType::class, [
                'label' => "<i class='icon-line-check'></i> Register"
            ]);
    }
}
