<?php

namespace Cap\Commercio\Form;

use Cap\Commercio\Entity\CommercioCommercant;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommercioCommercantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('uuid')
            ->add('profileMediaPath')
            ->add('mediaPath')
            ->add('canReceiveTender')
            ->add('facebookId')
            ->add('openSunday')
            ->add('commercialWordTitle')
            ->add('commercialWordMediaPath')
            ->add('legalEntity')
            ->add('vatNumber')
            ->add('phone')
            ->add('isMember')
            ->add('affiliationDate')
            ->add('archived')
            ->add('insertDate')
            ->add('modifyDate')
            ->add('stripeUserRef')
            ->add('commercialWordVideoPath')
            ->add('commercialWordDescription')
            ->add('legalEmail')
            ->add('legalPhone')
            ->add('legalFirstname')
            ->add('legalLastname')
            ->add('canReceiveNews')
            ->add('legalEmail2')
            ->add('facebookLink')
            ->add('twitterLink')
            ->add('linkedinLink')
            ->add('cta')
            ->add('hoursType')
            ->add('rightAccess')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CommercioCommercant::class,
        ]);
    }
}
