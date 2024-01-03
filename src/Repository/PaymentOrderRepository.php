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
     * @param int|null $year
     * @param bool|null $paid
     * @return PaymentOrder[]
     */
    public function search(?string $number, ?string $name, ?int $year, ?bool $paid): array
    {
        $qb = $this->createQb();

        if ($number) {
            $qb
                ->andWhere(
                    'upper(paymentOrder.orderNumber) LIKE upper(:number)'
                )
                ->setParameter('number', '%'.$number.'%');
        }

        if ($name) {
            $qb
                ->andWhere(
                    'upper(commercant.firstname) LIKE upper(:name) OR upper(commercant.companyName) LIKE upper(:name)'
                )
                ->setParameter('name', '%'.$name.'%');
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

    /**
     * @return PaymentOrder[]
     */
    public function findByBill(string $orderNumber): array
    {
        return $this->createQb()
            ->andWhere('paymentOrder.orderNumber = :id')
            ->setParameter('id', $orderNumber)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return PaymentOrder[]
     */
    public function findPaid(): array
    {
        return $this->createQb()
            ->andWhere('paymentOrder.isPaid = :paid')
            ->setParameter('paid', true)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return PaymentOrder[]
     */
    public function findBetweenDates(\DateTime $startDate, \DateTime $endDate): array
    {
        return $this->createQb()
            ->andWhere('paymentOrder.insertDate >= :start')
            ->setParameter('start', $startDate->format('Y-m-d'))
            ->andWhere('paymentOrder.insertDate < :end')
            ->setParameter('end', $endDate->format('Y-m-d'))
            ->getQuery()
            ->getResult();
    }

    public function findOneByWalletCodeOrder(string $orderCode): ?PaymentOrder
    {
        return $this->createQb()
            ->andWhere('paymentOrder.walletCodeOrder = :code')
            ->setParameter('code', $orderCode)
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
