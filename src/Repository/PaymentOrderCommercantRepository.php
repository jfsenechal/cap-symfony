<?php

namespace Cap\Commercio\Repository;

use Cap\Commercio\Doctrine\OrmCrudTrait;
use Cap\Commercio\Entity\PaymentOrderCommercant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PaymentOrderCommercant|null find($id, $lockMode = null, $lockVersion = null)
 * @method PaymentOrderCommercant|null findOneBy(array $criteria, array $orderBy = null)
 * @method PaymentOrderCommercant[]    findAll()
 * @method PaymentOrderCommercant[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PaymentOrderCommercantRepository extends ServiceEntityRepository
{
    use OrmCrudTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PaymentOrderCommercant::class);
    }

    /**
     * @return PaymentOrderCommercant[]
     */
    public function findAllOrdered(): array
    {
        return $this->createQueryBuilder('payment_order_commercant')
              ->orderBy('payment_order_commercant.insertDate', 'DESC')
              ->getQuery()
              ->getResult();
    }
}
