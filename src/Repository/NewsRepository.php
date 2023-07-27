<?php

namespace Cap\Commercio\Repository;

use Cap\Commercio\Doctrine\OrmCrudTrait;
use Cap\Commercio\Entity\NewsNews;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method NewsNews|null find($id, $lockMode = null, $lockVersion = null)
 * @method NewsNews|null findOneBy(array $criteria, array $orderBy = null)
 * @method NewsNews[]    findAll()
 * @method NewsNews[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NewsRepository extends ServiceEntityRepository
{
    use OrmCrudTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NewsNews::class);
    }

    /**
     * @return NewsNews[]
     */
    public function findAllOrdered(): array
    {
        return $this->createQueryBuilder('news')
            ->orderBy('news.insertDate', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
