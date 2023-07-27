<?php

namespace Cap\Commercio\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

trait CategoriesTrait
{
    #[ORM\ManyToMany(targetEntity: BlogCategory::class, cascade: ['remove'])]
    public array|Collection $categories;

    /**
     * @return Collection<int, BlogCategory>
     */
    public function getBlogCategorys(): Collection
    {
        return $this->categories;
    }

    public function addBlogCategory(BlogCategory $tag): self
    {
        if (!$this->categories->contains($tag)) {
            $this->categories->add($tag);
        }

        return $this;
    }

    public function removeBlogCategory(BlogCategory $tag): self
    {
        $this->categories->removeElement($tag);

        return $this;
    }
}
