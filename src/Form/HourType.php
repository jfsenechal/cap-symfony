<?php

namespace Cap\Commercio\Form;

use Cap\Commercio\Entity\CommercioCommercantHours;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class HourType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'isRdv',
                CheckboxType::class,
                [
                    'required' => false,
                ]
            )
            ->add(
                'isClosedAtLunch',
                CheckboxType::class,
                [
                    'required' => false,
                ]
            )
            ->add('morningStart', DateTimeType::class, [
                'required' => false,
                'widget' => 'single_text',
                'format' => "HH:mm",
                'html5' => false,
                'with_seconds' => false,
            ])
            ->add('morningEnd', DateTimeType::class, [
                'required' => false,
                'widget' => 'single_text',
                'format' => "HH:mm",
                'html5' => false,
            ])
            ->add('noonStart', DateTimeType::class, [
                'required' => false,
                'widget' => 'single_text',
                'format' => "HH:mm",
                'html5' => false,
            ])
            ->add('noonEnd', DateTimeType::class, [
                'required' => false,
                'widget' => 'single_text',
                'format' => "HH:mm",
                'html5' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class' => CommercioCommercantHours::class,
            ]
        );
    }
}
