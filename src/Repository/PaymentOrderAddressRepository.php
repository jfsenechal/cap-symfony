<?php

namespace Cap\Commercio\Repository;

use Cap\Commercio\Doctrine\OrmCrudTrait;
use Cap\Commercio\Entity\PaymentOrder;
use Cap\Commercio\Entity\PaymentOrderAddress;
use Cap\Commercio\Entity\PaymentOrderLines;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PaymentOrderAddress|null find($id, $lockMode = null, $lockVersion = null)
 * @method PaymentOrderAddress|null findOneBy(array $criteria, array $orderBy = null)
 * @method PaymentOrderAddress[]    findAll()
 * @method PaymentOrderAddress[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PaymentOrderAddressRepository extends ServiceEntityRepository
{
    use OrmCrudTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PaymentOrderAddress::class);
    }

    /**
     * @return PaymentOrderAddress[]
     */
    public function findAllOrdered(): array
    {
        return $this->createQueryBuilder('paymentOrderAddress')
            ->orderBy('paymentOrderAddress.insertDate', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return PaymentOrderAddress[]
     */
    public function findByOrder(PaymentOrder $order): array
    {
        return $this->createQueryBuilder('paymentOrderAddress')
            ->andWhere('paymentOrderAddress.order = :order')
            ->setParameter('order', $order)
            ->getQuery()
            ->getResult();
    }

    public function findOneByOrder(PaymentOrder $order): ?PaymentOrderAddress
    {
        return $this->createQueryBuilder('paymentOrderAddress')
            ->andWhere('paymentOrderAddress.order = :order')
            ->setParameter('order', $order)
            ->getQuery()
            ->getOneOrNullResult();
    }

}
