<?php

namespace Cap\Commercio\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\FormBuilderInterface;

class OrderSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', SearchType::class, [
                'label' => 'Nom',
                'required' => false,
            ])
            ->add('statut', ChoiceType::class, [
                'choices' => ["Temporaire" => 1, "En cours" => 2, "Traité" => 3, "Archivé" => 4, "Error" => 5],
                'required' => false,
            ]);
    }

}
