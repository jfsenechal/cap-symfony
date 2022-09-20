<?php

namespace Cap\Commercio\Repository;

use Cap\Commercio\Doctrine\OrmCrudTrait;
use Cap\Commercio\Entity\CommercioCommercant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CommercioCommercant|null find($id, $lockMode = null, $lockVersion = null)
 * @method CommercioCommercant|null findOneBy(array $criteria, array $orderBy = null)
 * @method CommercioCommercant[]    findAll()
 * @method CommercioCommercant[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommercioCommercantRepository extends ServiceEntityRepository
{
    use OrmCrudTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CommercioCommercant::class);
    }

    /**
     * @return CommercioCommercant[]
     */
    public function findAllOrdered():array
    {
      return  $this->createQueryBuilder('paymentOrder')
            ->orderBy('paymentOrder.insertDate', 'DESC')
            ->getQuery()
            ->getResult();
    }

}
