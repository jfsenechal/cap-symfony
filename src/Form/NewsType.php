<?php

namespace Cap\Commercio\Form;

use Cap\Commercio\Entity\News;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image as ImageConstraint;

class NewsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Intitule',
                'required' => true,
            ])
            ->add('description', TextareaType::class, [
                'attr' => ['rows' => 5],
            ])
            ->add('sendByMail', CheckboxType::class, [
                'label' => 'Envoyer par mail',
                'required' => false,
            ])
            ->add('sendToBottin', CheckboxType::class, [
                'label' => 'Envoyer aux non membres (bottin)',
                'required' => false,
            ])
            ->add('image', FileType::class, [
                'label' => 'Image',
                'required' => true,
                'constraints' => [
                    new ImageConstraint(),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => News::class,
        ]);
    }
}
