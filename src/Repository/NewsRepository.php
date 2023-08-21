<?php

namespace Cap\Commercio\Repository;

use Cap\Commercio\Doctrine\OrmCrudTrait;
use Cap\Commercio\Entity\News;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method News|null find($id, $lockMode = null, $lockVersion = null)
 * @method News|null findOneBy(array $criteria, array $orderBy = null)
 * @method News[]    findAll()
 * @method News[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NewsRepository extends ServiceEntityRepository
{
    use OrmCrudTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, News::class);
    }

    /**
     * @return News[]
     */
    public function findAllOrdered(): array
    {
        return $this->createQueryBuilder('news')
            ->orderBy('news.insertDate', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return News[]
     */
    public function findNotSended(): array
    {
        return $this->createQueryBuilder('news')
            ->andWhere('news.isSend = false')
            ->andWhere('news.sendByMail = true')
            ->orderBy('news.insertDate', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
