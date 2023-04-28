<?php

namespace Cap\Commercio\Repository;

use Cap\Commercio\Doctrine\OrmCrudTrait;
use Cap\Commercio\Entity\BlogCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method BlogCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method BlogCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method BlogCategory[]    findAll()
 * @method BlogCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BlogCategoryRepository extends ServiceEntityRepository
{
    use OrmCrudTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BlogCategory::class);
    }

    /**
     * @return BlogCategory[]
     */
    public function findAllOrdered(): array
    {
        return $this->createQueryBuilder('blog_tag')
            ->orderBy('blog_tag.insertDate', 'DESC')
            ->getQuery()
            ->getResult();
    }

}