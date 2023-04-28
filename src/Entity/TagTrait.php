<?php

namespace Cap\Commercio\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

trait TagTrait
{
    #[ORM\ManyToMany(targetEntity: BlogTag::class, cascade: ['remove'])]
    public array|Collection $tags;

    /**
     * @return Collection<int, BlogTag>
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(BlogTag $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags->add($tag);
        }

        return $this;
    }

    public function removeTag(BlogTag $tag): self
    {
        $this->tags->removeElement($tag);

        return $this;
    }
}