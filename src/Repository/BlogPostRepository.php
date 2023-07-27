<?php

namespace Cap\Commercio\Repository;

use Cap\Commercio\Doctrine\OrmCrudTrait;
use Cap\Commercio\Entity\BlogPost;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method BlogPost|null find($id, $lockMode = null, $lockVersion = null)
 * @method BlogPost|null findOneBy(array $criteria, array $orderBy = null)
 * @method BlogPost[]    findAll()
 * @method BlogPost[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BlogPostRepository extends ServiceEntityRepository
{
    use OrmCrudTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BlogPost::class);
    }

    /**
     * @return BlogPost[]
     */
    public function findAllOrdered(): array
    {
        return $this->createQueryBuilder('blog_post')
            ->leftJoin('blog_post.author', 'author', 'WITH')
            ->addSelect('author')
            ->orderBy('blog_post.insertDate', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return BlogPost[]
     */
    public function search(string $name): array
    {
        return $this->createQueryBuilder('blog_post')
            ->leftJoin('blog_post.author', 'author', 'WITH')
            ->addSelect('author')
            ->orderBy('blog_post.insertDate', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
