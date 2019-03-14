<?php

namespace App\Form;

use App\Entity\Listing;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ListType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $choicesVisibility = [
            'Public' => '1',
            'Privé' => '0'
        ];
        $choicesType = [
            'Oui' => '1',
            'non' => '0'
        ];
        $builder
            ->add('name')
            ->add('visibility', ChoiceType::class, [
                'choices' => $choicesVisibility,
                'expanded' => true,  // => boutons
            ])
            ->add('type', ChoiceType::class, [
                'choices' => $choicesType,
                'expanded' => true,  // => boutons
            ])
            ->add('description')

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Listing::class,
        ]);
    }
}
