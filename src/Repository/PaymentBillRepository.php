<?php

namespace Cap\Commercio\Repository;

use Cap\Commercio\Doctrine\OrmCrudTrait;
use Cap\Commercio\Entity\CommercioCommercant;
use Cap\Commercio\Entity\PaymentBill;
use Cap\Commercio\Entity\PaymentOrder;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
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
        return $this->createQb()
            ->getQuery()
            ->getResult();
    }

    /**
     * @return PaymentBill[]
     */
    public function findByCommercant(CommercioCommercant $commercant): array
    {
        return $this->createQb()
            ->andWhere('orderCap.commercantId = :commercant')
            ->setParameter('commercant', $commercant)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return PaymentBill[]
     */
    public function search(?string $number, ?string $name, ?int $year, ?bool $paid): array
    {
        $qb = $this->createQb();

        if ($number) {
            $qb
                ->andWhere(
                    'upper(payment_bill.billNumber) LIKE upper(:number)'
                )
                ->setParameter('number', '%' . $number . '%');
        }

        if ($name) {
            $qb
                ->andWhere(
                    'upper(commercant.firstname) LIKE upper(:name) OR upper(commercant.companyName) LIKE upper(:name)'
                )
                ->setParameter('name', '%' . $name . '%');
        }

        if ($year) {
            $qb
                ->andWhere('YEAR(paymentOrder.insertDate) = :year')
                ->setParameter('year', $year);
        }

        if ($paid !== null) {
            $qb
                ->andWhere('paymentOrder.isPaid = :paid')
                ->setParameter('paid', $paid);
        }

        return $qb->getQuery()
            ->getResult();
    }

    /**
     * @return PaymentBill|null
     * @throws NonUniqueResultException
     */
    public function findOneByOrder(PaymentOrder $order): ?PaymentBill
    {
        return $this->createQb()
            ->andWhere('payment_bill.order = :order')
            ->setParameter('order', $order)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @return PaymentBill[]
     */
    public function findMultipleByOrder(PaymentOrder $order): array
    {
        return $this->createQb()
            ->andWhere('payment_bill.order = :order')
            ->setParameter('order', $order)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return PaymentBill[]
     */
    public function countBetweenDates(string $startDate, string $endDate): array
    {
        return $this->createQb()
            ->andWhere('payment_bill.insertDate BETWEEN :begin and :end')
            ->setParameter('begin', $startDate)
            ->setParameter('end', $endDate)
            ->getQuery()
            ->getResult();
    }

    private function createQb(): QueryBuilder
    {
        return $this->createQueryBuilder('payment_bill')
            ->leftJoin('payment_bill.order', 'orderCap', 'WITH')
            ->leftJoin('orderCap.orderCommercant', 'orderCommercant', 'WITH')
            ->addSelect('orderCap', 'orderCommercant')
            ->orderBy('payment_bill.insertDate', 'DESC');
    }
}
