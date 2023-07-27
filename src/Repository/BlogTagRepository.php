<?php

namespace Cap\Commercio\Repository;

use Cap\Commercio\Doctrine\OrmCrudTrait;
use Cap\Commercio\Entity\BlogTag;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method BlogTag|null find($id, $lockMode = null, $lockVersion = null)
 * @method BlogTag|null findOneBy(array $criteria, array $orderBy = null)
 * @method BlogTag[]    findAll()
 * @method BlogTag[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BlogTagRepository extends ServiceEntityRepository
{
    use OrmCrudTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BlogTag::class);
    }

    /**
     * @return BlogTag[]
     */
    public function findAllOrdered(): array
    {
        return $this->createQueryBuilder('blog_tag')
            ->orderBy('blog_tag.insertDate', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
