<?php

namespace Cap\Commercio\Form;

use Cap\Commercio\Entity\CommercioCommercantHoliday;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class HolidayType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'beginDate',
                DateType::class,
                [
                    'required' => true,
                    'widget' => 'single_text',
                ]
            )
            ->add(
                'endDate',
                DateType::class,
                [
                    'required' => true,
                    'widget' => 'single_text',
                ]
            )
            ->add(
                'note',
                TextareaType::class,
                [
                    'required' => true,
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class' => CommercioCommercantHoliday::class,
            ]
        );
    }
}
