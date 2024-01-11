<?php

namespace Cap\Commercio\Form;

use Cap\Commercio\Entity\CommercioCommercant;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CheckMemberType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('isMember', CheckboxType::class, [
                'required' => false,
                'label' => 'Affilier à Cap ?',
                'help' => 'Décochez la case pour désaffilier',
            ])
            ->add('affiliationDate', DateType::class, [
                'required' => false,
                'label' => 'Date d\'affiliation',
                'widget' => 'single_text',
                'help' => 'Si affilié, à partir de quelle date ? Sinon la date est mise à nul',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CommercioCommercant::class,
        ]);
    }
}
