<?php

namespace Cap\Commercio\Repository;

use Cap\Commercio\Doctrine\OrmCrudTrait;
use Cap\Commercio\Entity\BlogCategoryPost;
use Cap\Commercio\Entity\BlogPost;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method BlogCategoryPost|null find($id, $lockMode = null, $lockVersion = null)
 * @method BlogCategoryPost|null findOneBy(array $criteria, array $orderBy = null)
 * @method BlogCategoryPost[]    findAll()
 * @method BlogCategoryPost[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BlogCategoryPostRepository extends ServiceEntityRepository
{
    use OrmCrudTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BlogCategoryPost::class);
    }

    /**
     * @return BlogCategoryPost[]
     */
    public function findByPost(BlogPost $blog_post): array
    {
        return $this->createQueryBuilder('blog_tag_post')
            ->leftJoin('blog_tag_post.post', 'post', 'WITH')
            ->addSelect('post')
            ->andWhere('post = :post')
            ->setParameter('post', $blog_post)
            ->getQuery()
            ->getResult();
    }
}
