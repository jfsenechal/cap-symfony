<?php

namespace Cap\Commercio\Repository;

use Cap\Commercio\Entity\BlogPost;
use Doctrine\Common\Collections\ArrayCollection;

class RepositoryUtils
{
    public function __construct(
        private readonly BlogTagPostRepository  $blogTagPostRepository,
        private readonly BlogCategoryPostRepository $blogCategoryPostRepository
    )
    {
    }

    public function setTagsToPost(BlogPost $blogPost): void
    {
        $blogPost->tags = new ArrayCollection();
        foreach ($this->blogTagPostRepository->findByPost($blogPost) as $item) {
            $blogPost->addTag($item->getTag());
        }
    }

    public function setCategoriesToPost(BlogPost $blogPost): void
    {
        $blogPost->categories = new ArrayCollection();
        foreach ($this->blogCategoryPostRepository->findByPost($blogPost) as $item) {
            $blogPost->addBlogCategory($item->getCategory());
        }
    }
}