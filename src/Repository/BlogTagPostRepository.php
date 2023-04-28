<?php

namespace Cap\Commercio\Repository;

use Cap\Commercio\Doctrine\OrmCrudTrait;
use Cap\Commercio\Entity\BlogPost;
use Cap\Commercio\Entity\BlogTagPost;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method BlogTagPost|null find($id, $lockMode = null, $lockVersion = null)
 * @method BlogTagPost|null findOneBy(array $criteria, array $orderBy = null)
 * @method BlogTagPost[]    findAll()
 * @method BlogTagPost[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BlogTagPostRepository extends ServiceEntityRepository
{
    use OrmCrudTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BlogTagPost::class);
    }

    /**
     * @return BlogTagPost[]
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