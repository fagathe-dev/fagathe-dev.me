<?php

namespace App\Form\Website;

use App\Entity\Contact;
use App\Enum\ContactSubjectEnum;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // dd(ContactSubjectEnum::choices());
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Adresse e-mail',
                'required' => true,
            ])
            ->add('telephone', TelType::class, [
                'label' => 'Numéro de téléphone'
            ])
            ->add('fullname', TextType::class, [
                'label' => 'Nom prénom'
            ])
            ->add('subject', ChoiceType::class, [
                'label' => 'Objet',
                'choices' => ContactSubjectEnum::choices(),
                'expanded' => false,
                'multiple' => false,
            ])
            ->add('message', TextareaType::class, [
                'label' => 'Message',
                'attr' => ['rows' => 5],
                'data' => "Bonjour Frédérick,\r",
                'required' => true,
            ])
            ->add('send', SubmitType::class, [
                'label' => 'Envoyer',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}
