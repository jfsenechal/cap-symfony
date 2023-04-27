<?php

namespace Cap\Commercio\Form;

use Cap\Commercio\Entity\BlogPost;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BlogPostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Intitule',
                'required' => true,
            ])
            ->add('summary', TextareaType::class, [
                'label' => 'Résumé',
                'required' => false,
            ])
            ->add('postText', TextareaType::class, [
                'label' => 'Texte',
                'required' => true,
                'attr'=>['rows'=>5]
            ])
            ->add('archived', CheckboxType::class, [
                'label' => 'Archivé',
                'required' => false,
            ])
            ->add('publishDate', DateType::class, [
                'label' => 'Publié le',
                'widget'=>'single_text',
                'required' => true,
            ])
            ->add('title', TextType::class, [
                'label' => 'Intitule',
                'required' => true,
            ])
      ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => BlogPost::class,
        ]);
    }

}
