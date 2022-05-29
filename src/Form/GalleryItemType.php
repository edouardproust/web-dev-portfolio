<?php

namespace App\Form;

use App\Entity\GalleryItem;
use App\Helper\FileHelper;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GalleryItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'itemFile',
                VichImageType::class,
                [
                    'label' => FileHelper::getLabel('FILE_TYPE'),
                    'constraints' => [
                        new File([
                            'maxSize' => FileHelper::getMaxSize('FILE_TYPE'),
                            'mimeTypes' => FileHelper::getMimeTypes('IMAGE_TYPE', 'VIDEO_TYPE', 'EMBED_TYPE'),
                            'mimeTypesMessage' => FileHelper::getMimeTypesMessage('FILE_TYPE')
                        ])
                    ]
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => GalleryItem::class,
        ]);
    }
}
