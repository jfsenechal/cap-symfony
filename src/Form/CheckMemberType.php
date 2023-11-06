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
                'label' => 'Est-il affilié à Cap ?',
                'help' => 'Cochez pour oui',
            ])
            ->add('affiliationDate', DateType::class, [
                'required' => false,
                'label' => 'Date d\'affiliation',
                'widget' => 'single_text',
                'help' => 'Si affilié, à quelle date ? Sinon la valeur n\'est pas prise en compte',
            ])
            ->add('sendMailExpired', CheckboxType::class, [
                'label' => 'Envoyer un mail',
                'help' => 'Envoyer un mail pour prévenir de l\'expiration du membre',
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
