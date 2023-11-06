<?php

namespace Cap\Commercio\Repository;

use Cap\Commercio\Doctrine\OrmCrudTrait;
use Cap\Commercio\Entity\CommercioCommercant;
use Cap\Commercio\Entity\EventEvent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method EventEvent|null find($id, $lockMode = null, $lockVersion = null)
 * @method EventEvent|null findOneBy(array $criteria, array $orderBy = null)
 * @method EventEvent[]    findAll()
 * @method EventEvent[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PaymentOrderStatutRepository extends ServiceEntityRepository
{
    use OrmCrudTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EventEvent::class);
    }

    /**
     * @return EventEvent[]
     */
    public function findAllOrdered(): array
    {
        return $this->createQueryBuilder('event')
            ->orderBy('event.insertDate', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findByCommercant(CommercioCommercant $commercant): array
    {
        return $this->createQb()
            ->andWhere('payment_order_statut.commercioCommercant = :shop')
            ->setParameter('shop', $commercant)
            ->getQuery()->getResult();
    }

    private function createQb(): QueryBuilder
    {
        return $this->createQueryBuilder('payment_order_statut')
            ->leftJoin('payment_order_statut.commercioCommercant', 'commercioCommercant', 'WITH')
            ->addSelect('commercioCommercant');
    }

}
