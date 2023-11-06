<?php

namespace Cap\Commercio\Form;

use Cap\Commercio\Entity\CommercioCommercant;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MemberType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('legalEntity', TextType::class, [
                'required' => true,
                'label' => 'Entité légale',
            ])
            ->add('vatNumber', TextType::class, [
                'required' => true,
                'label' => 'Numéro de BCE ou de Tva',
            ])
            ->add('legalFirstname', TextType::class, [
                'required' => true,
            ])
            ->add('legalLastname', TextType::class, [
                'required' => true,
            ])
            ->add('legalEmail', TextType::class, [
                'required' => true,
                'label' => 'Email',
            ])
            ->add('legalEmail2', TextType::class, [
                'required' => false,
            ])
            ->add('legalPhone', TextType::class, [
                'required' => false,
            ])
            ->add('address', AddressType::class)
            ->add('affiliationDate', DateType::class, [
                'required' => true,
                'label' => 'Date d\'affiliation',
                'widget' => 'single_text',
            ])
            ->add('generateOrder', CheckboxType::class, [
                'required' => false,
                'label' => 'Générer un bon de commande',
                'help' => 'Faut-il générer un bon de commande ?',
            ])
            ->add('address', AddressType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CommercioCommercant::class,
        ]);
    }
}
