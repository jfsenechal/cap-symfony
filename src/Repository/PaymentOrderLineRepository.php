<?php

namespace Cap\Commercio\Repository;

use Cap\Commercio\Doctrine\OrmCrudTrait;
use Cap\Commercio\Entity\PaymentOrder;
use Cap\Commercio\Entity\PaymentOrderLines;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PaymentOrderLines|null find($id, $lockMode = null, $lockVersion = null)
 * @method PaymentOrderLines|null findOneBy(array $criteria, array $orderBy = null)
 * @method PaymentOrderLines[]    findAll()
 * @method PaymentOrderLines[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PaymentOrderLineRepository extends ServiceEntityRepository
{
    use OrmCrudTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PaymentOrderLines::class);
    }

    /**
     * @return PaymentOrderLines[]
     */
    public function findAllOrdered(): array
    {
        return $this->createQueryBuilder('paymentOrderLine')
            ->orderBy('paymentOrderLine.insertDate', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findByOrder(PaymentOrder $order): ?PaymentOrderLines
    {
        return $this->createQueryBuilder('paymentOrderLine')
            ->andWhere('paymentOrderLine.order = :order')
            ->setParameter('order', $order)
            ->getQuery()
            ->getOneOrNullResult();
    }

}
