<?php

namespace Cap\Commercio\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Cap\Commercio\Entity\AddressAddress;

class AddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('street1', TextType::class, [
                'required' => true,
                'label' => 'Rue et numéro',
                'attr' => ['autocomplete' => 'off'],
            ])
            ->add('zipcode', TextType::class, [
                'required' => true,
                'label' => 'Code postal',
            ])
            ->add('city', TextType::class, [
                'required' => true,
                'label' => 'Localité',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => AddressAddress::class,
        ]);
    }
}
