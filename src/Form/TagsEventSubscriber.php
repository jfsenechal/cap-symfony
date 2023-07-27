<?php

namespace Cap\Commercio\Form;

use Cap\Commercio\Entity\BlogTag;
use Cap\Commercio\Repository\BlogTagRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

/**
 * Ajout automatique de new tag
 */
class TagsEventSubscriber implements EventSubscriberInterface
{
    public function __construct(private readonly BlogTagRepository $tagRepository)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            FormEvents::PRE_SUBMIT => 'onPreSetData',
        ];
    }

    public function onPreSetData(FormEvent $event): void
    {
        $data = $event->getData();
        $form = $event->getForm();
        $tags = $data['tags'];
        foreach ($tags['autocomplete'] as $key => $value) {
            $id = (int)$value;
            //new tag
            if ($id == 0) {
                $tag = $this->createTag($value);
                $tags['autocomplete'][$key] = (string)$tag->getId();
            }
        }
        $data['tags'] = $tags;
        $event->setData($data);
    }

    private function createTag(string $value): BlogTag
    {
        $tag = new BlogTag();
        $tag->setName($value);
        $this->tagRepository->insert($tag);

        return $tag;
    }
}
