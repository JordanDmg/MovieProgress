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
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class EditUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $choicesGender = [
            'Masculin' => '1',
            'Feminin' => '0'
        ];
        $builder
            ->add('email', EmailType::class, [
                 'data' => $options['data']->getEmail(),
                
            ])
            ->add('username', TextType::class, [
                'data'  =>$options['data']->getUsername(),
            ])
            ->add('name', TextType::class, [
                'data'  =>$options['data']->getName(),
            ])
            ->add('firstName', TextType::class, [
                'data'  =>$options['data']->getFirstName(),
            ])
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
