<?php

namespace App\Form\Admin;

use App\Entity\Skill;
use App\Enum\LevelSkillEnum;
use App\Enum\TypeSkillEnum;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SkillType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom',
                'required' => false,
            ])
            ->add('level', ChoiceType::class, [
                'label' => 'Niveau',
                'required' => false,
                'placeholder' => 'Sélectionner un niveau',
                'choices' => LevelSkillEnum::choices(),
            ])
            ->add('type', ChoiceType::class, [
                'label' => 'Type',
                'required' => false,
                'placeholder' => 'Sélectionner un type',
                'choices' => TypeSkillEnum::choices(),
            ])
            ->add('logo', TextType::class, [
                'label' => 'Logo',
                'required' => false,
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Enregistrer',
                'attr' => [
                    'class' => 'btn btn-primary'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Skill::class,
        ]);
    }
}
