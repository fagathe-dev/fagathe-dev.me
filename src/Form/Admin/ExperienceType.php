<?php

namespace App\Form\Admin;

use App\Entity\Experience;
use App\Enum\TypeExperienceEnum;
use App\Helpers\DateTimeHelperTrait;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ExperienceType extends AbstractType
{
    use DateTimeHelperTrait;

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom',
            ])
            ->add('type', ChoiceType::class, [
                'choices' => TypeExperienceEnum::choices(),
                'label' => 'Type',
            ])
            ->add('start_year', ChoiceType::class, [
                'label' => 'Année de début',
                'choices' => $this->years(),
            ])
            ->add('end_year', ChoiceType::class, [
                'label' => 'Année de fin',
                'choices' => $this->years(),
                'required' => false,
            ])
            ->add('tasks', CollectionType::class, [
                'label' => 'Tâches',
                'allow_add' => true,
                'allow_delete' => true,
                'delete_empty' => true,
                'entry_options' => ['label' => false],
                'by_reference' => false,
            ])
            ->add('place', TextType::class, [
                'label' => 'Lieu',
                'required' => false,
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
            'data_class' => Experience::class,
            'csrf_protection' => false,
        ]);
    }
}
