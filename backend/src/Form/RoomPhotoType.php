<?php

namespace App\Form;

use App\Entity\RoomPhoto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class RoomPhotoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('image', FileType::class, [
                'label' => 'Plik zdjęcia',
                'mapped' => false,
                'required' => true,
                'constraints' => [
                    new File(
                        [
                            'maxSize' => '8196k',
                            'mimeTypes' => ['image/jpeg', 'image/png'],
                            'mimeTypesMessage' => 'Please upload a valid image'
                        ]
                    )
                ]
            ])
            ->add('room_id');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => RoomPhoto::class,
        ]);
    }
}
