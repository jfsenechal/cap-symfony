<?php

namespace Cap\Commercio\Form;

use Cap\Commercio\Entity\BlogTag;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Count;
use Symfony\UX\Autocomplete\Form\AsEntityAutocompleteField;
use Symfony\UX\Autocomplete\Form\ParentEntityAutocompleteType;

#[AsEntityAutocompleteField]
class TagsAutocompleteField extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'required' => false,
            'class' => BlogTag::class,
            'label' => 'Tags',
            'choice_label' => 'name',
            'multiple' => true,
            'tom_select_options' => [
                'create' => true,
                'createOnBlur' => true,
            ],
            'allow_options_create' => true,
            'constraints' => [
                // new Count(min: 1, minMessage: 'We need to eat *something*'),
            ],
        ]);
    }

    public function getParent(): string
    {
        return ParentEntityAutocompleteType::class;
    }
}
