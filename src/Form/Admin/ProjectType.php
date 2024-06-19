<?php

namespace App\Form\Admin;

use App\Entity\Project;
use App\Enum\TypeProjectEnum;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class ProjectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom du projet'
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'required' => false
            ])
            ->add('uploadedImage', FileType::class, [
                'label' => 'Image',

                // unmapped means that this field is not associated to any entity property
                'mapped' => false,

                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => false,

                // unmapped fields can't define their validation using attributes
                // in the associated entity, so you can use the PHP constraint classes
                'constraints' => [
                    new File([
                        'maxSize' => '10m',
                        'mimeTypes' => [
                            'image/bmp',
                            'image/gif',
                            'image/jpeg',
                            'image/png',
                            'image/svg+xml',
                            'image/tiff',
                            'image/webp',
                            'image/x-icon',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid PDF document',
                    ])
                ],
            ])
            ->add('type', ChoiceType::class, [
                'label' => 'Type de projet',
                'required' => false,
                'choices' => TypeProjectEnum::choices(),
                'multiple' => false,
                'expanded' => false,
            ])
            ->add('tasks', CollectionType::class, [
                'label' => 'Tâches',
                'allow_add' => true,
                'allow_delete' => true,
                'delete_empty' => true,
                'entry_options' => ['label' => false],
                'by_reference' => false,
            ])
            ->add('published', CheckboxType::class, [
                'required' => false,
                'label' => 'Publier',
            ])
            ->add('url', UrlType::class, [
                'required' => false,
                'label' => 'URL du projet',
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Enregistrer',
                'attr' => [
                    'class' => 'btn-primary'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Project::class,
        ]);
    }
}
