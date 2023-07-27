<?php

namespace Cap\Commercio\Form;

use Cap\Commercio\Entity\CommercioCommercant;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommercantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('commercialWordTitle', TextType::class, [
                'required' => false,
            ])
            ->add('legalEntity', TextType::class, [
                'required' => true,
            ])
            ->add('vatNumber', TextType::class, [
                'required' => false,
            ])
            ->add('phone', TextType::class, [
                'required' => false,
            ])
            ->add('commercialWordDescription', TextareaType::class, [
                'required' => false,
                'attr' => ['rows' => 5]
            ])
            ->add('legalEmail', TextType::class, [
                'required' => true,
            ])
            ->add('legalEmail2', TextType::class, [
                'required' => false,
            ])
            ->add('legalPhone', TextType::class, [
                'required' => false,
            ])
            ->add('legalFirstname', TextType::class, [
                'required' => false,
            ])
            ->add('legalLastname', TextType::class, [
                'required' => false,
            ])
            ->add('facebookLink', TextType::class, [
                'required' => false,
            ])
            ->add('twitterLink', TextType::class, [
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CommercioCommercant::class,
        ]);
    }
}
