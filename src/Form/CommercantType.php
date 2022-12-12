<?php

namespace Cap\Commercio\Form;

use Cap\Commercio\Entity\CommercioCommercant;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommercantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('legalEntity')
            ->add('vatNumber')
            ->add('phone')
            ->add('isMember')
            ->add('affiliationDate')
            ->add('legalEmail')
            ->add('legalPhone')
            ->add('legalFirstname')
            ->add('legalLastname')
            ->add('facebookLink')
            ->add('twitterLink')
            ->add('linkedinLink');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CommercioCommercant::class,
        ]);
    }
}