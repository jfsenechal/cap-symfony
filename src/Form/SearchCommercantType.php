<?php

namespace Cap\Commercio\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\FormBuilderInterface;

class SearchCommercantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', SearchType::class, [
                'label' => 'Mot clef',
                'required' => false,
            ])
            ->add('isMember', ChoiceType::class, [
                'label' => 'Membre cap',
                'required' => false,
                'choices' => ['Oui' => 1, 'Non' => 0],
            ]);
    }
}