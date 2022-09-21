<?php

namespace Cap\Commercio\Repository;

use Cap\Commercio\Doctrine\OrmCrudTrait;
use Cap\Commercio\Entity\PaymentBill;
use Cap\Commercio\Entity\PaymentOrder;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PaymentBill|null find($id, $lockMode = null, $lockVersion = null)
 * @method PaymentBill|null findOneBy(array $criteria, array $orderBy = null)
 * @method PaymentBill[]    findAll()
 * @method PaymentBill[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PaymentBillRepository extends ServiceEntityRepository
{
    use OrmCrudTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PaymentBill::class);
    }

    /**
     * @return PaymentBill[]
     */
    public function findAllOrdered(): array
    {
        return $this->createQueryBuilder('payment_bill')
            ->leftJoin('payment_bill.order', 'ordercap', 'WITH')
            ->leftJoin('ordercap.orderCommercant', 'orderCommercant', 'WITH')
            ->addSelect('ordercap', 'orderCommercant')
            ->orderBy('payment_bill.insertDate', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return PaymentBill[]
     */
    public function findByOrder(PaymentOrder $order): array
    {
        return $this->createQueryBuilder('paymentOrderAddress')
            ->andWhere('paymentOrderAddress.order = :order')
            ->setParameter('order', $order)
            ->getQuery()
            ->getResult();
    }

}
