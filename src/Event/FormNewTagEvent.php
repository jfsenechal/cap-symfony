<?php

namespace Cap\Commercio\Event;

use Cap\Commercio\Entity\BlogTag;
use Cap\Commercio\Repository\BlogTagRepository;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Event\PostSubmitEvent;
use Symfony\Component\Form\Event\PreSubmitEvent;
use Symfony\Component\Form\Event\SubmitEvent;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

/**
 * Permet d'ajouter des tags non persistes
 */
class FormNewTagEvent implements EventSubscriberInterface
{
    public function __construct(private BlogTagRepository $tagRepository)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            // FormEvents::PRE_SET_DATA => 'onPreSetData',
            FormEvents::PRE_SUBMIT => 'onPreSubmit',
            //  FormEvents::POST_SET_DATA => 'onPostSetData',
            //  FormEvents::POST_SUBMIT => 'onPostSubmit',
            //  FormEvents::SUBMIT => 'onSubmit',
        ];
    }

    //2 data avant generate form
    //avant submit
    public function onPostSetData(FormEvent $event): void
    {
    }

    //5
    public function onPostSubmit(PostSubmitEvent $event): void
    {
    }

    //1, data avant generate form
    //avant submit
    public function onPreSetData(FormEvent $event): void
    {
    }

    //3 juste apres submit
    //data populate
    public function onPreSubmit(PreSubmitEvent $event): void
    {
        $fiche = $event->getData();
        if (!isset($fiche['tags'])) {
            return;
        }
        $tags = $fiche['tags']['autocomplete'];
        $tagsSave = [];

        foreach ($tags as $key => $value) {
            if ((int)$value > 0) {
                $tagsSave[] = $value;
                continue;
            }
            try {
                if (!$this->tagRepository->findOneByName($value)) {
                    $tag = new BlogTag();
                    $tag->setName($value);
                    $this->tagRepository->persist($tag);
                    $this->tagRepository->flush();
                    $this->tagRepository->flush();
                    $tagsSave[] = $tag->getId();
                }
            } catch (NonUniqueResultException $e) {
            }
        }

        $fiche['tags']['autocomplete'] = $tagsSave;
        $event->setData($fiche);
    }

    //4 tags invalide disparu
    public function onSubmit(SubmitEvent $event): void
    {
    }
}
