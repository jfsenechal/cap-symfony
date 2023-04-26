<?php

namespace Cap\Commercio\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\LessThan;

class OrderSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', SearchType::class, [
                'label' => 'Nom',
                'required' => false,
            ])
            ->add('year', IntegerType::class, [
                'label' => 'Année',
                'required' => false,
                'constraints' => [new GreaterThan(2000), new LessThan(2100)],
            ])
            ->add('paid', ChoiceType::class, [
                'label' => 'Payé ?',
                'choices' => ['Oui' => true, 'Non' => false],
                'placeholder' => 'Peu importe',
                'required' => false,
            ]);
    }

}
