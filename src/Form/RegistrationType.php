<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $choicesGender = [
            'Masculin' => '1',
            'Feminin' => '0'
        ];

        $builder
            ->add('email', EmailType::class)
            ->add('username', TextType::class)
            ->add('name', TextType::class)
            ->add('password', PasswordType::class)
            ->add('confirm_password', PasswordType::class)
            ->add('firstName', TextType::class)
            ->add('birthdate', DateType::class, array(
                'widget' => 'choice',
                'format' => 'dd-MM-yyyy',
                'years' => range(date('Y'), date('Y')-100),
            ))
            ->add('gender', ChoiceType::class, [
                'choices' => $choicesGender,
                'expanded' => true,  // => boutons
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
