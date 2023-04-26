<?php

namespace Cap\Commercio\Repository;

use Cap\Commercio\Doctrine\OrmCrudTrait;
use Cap\Commercio\Entity\PaymentOrder;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PaymentOrder|null find($id, $lockMode = null, $lockVersion = null)
 * @method PaymentOrder|null findOneBy(array $criteria, array $orderBy = null)
 * @method PaymentOrder[]    findAll()
 * @method PaymentOrder[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PaymentOrderRepository extends ServiceEntityRepository
{
    use OrmCrudTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PaymentOrder::class);
    }

    /**
     * @return PaymentOrder[]
     */
    public function findAllOrdered(): array
    {
        return $this->createQb()
            ->getQuery()
            ->getResult();
    }

    /**
     * @param string|null $name
     * @return PaymentOrder[]
     */
    public function search(?string $name): array
    {
        return $this->createQb()
            ->andWhere(
                'upper(commercant.firstname) LIKE upper(:name) OR upper(commercant.companyName) LIKE upper(:name)'
            )
            ->setParameter('name', '%'.$name.'%')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return PaymentOrder[]
     */
    public function findByCommercantId(int $commercantId): array
    {
        return $this->createQb()
            ->andWhere('paymentOrder.commercantId = :id')
            ->setParameter('id', $commercantId)
            ->getQuery()
            ->getResult();
    }

    private function createQb(): QueryBuilder
    {
        return $this->createQueryBuilder('paymentOrder')
            ->leftJoin('paymentOrder.orderCommercant', 'commercant', 'WITH')
            ->addSelect('commercant')
            ->orderBy('paymentOrder.insertDate', 'DESC');
    }
}
