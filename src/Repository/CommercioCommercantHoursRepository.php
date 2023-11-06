<?php

namespace Cap\Commercio\Repository;

use Cap\Commercio\Doctrine\OrmCrudTrait;
use Cap\Commercio\Entity\CommercioCommercant;
use Cap\Commercio\Entity\CommercioCommercantHours;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CommercioCommercantHours|null find($id, $lockMode = null, $lockVersion = null)
 * @method CommercioCommercantHours|null findOneBy(array $criteria, array $orderBy = null)
 * @method CommercioCommercantHours[]    findAll()
 * @method CommercioCommercantHours[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommercioCommercantHoursRepository extends ServiceEntityRepository
{
    use OrmCrudTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CommercioCommercantHours::class);
    }

    /**
     * @return CommercioCommercantHours[]
     */
    public function findAllOrdered(): array
    {
        return $this->createQb()
            ->getQuery()
            ->getResult();
    }

    /**
     * @return CommercioCommercantHours[]
     */
    public function findByCommercant(CommercioCommercant $commercioCommercant): array
    {
        return $this->createQb()
            ->andWhere('commercio_commercant_hours.commercioCommercant = :commercant')
            ->setParameter('commercant', $commercioCommercant)
            ->getQuery()->getResult();
    }

    private function createQb(): QueryBuilder
    {
        return $this->createQueryBuilder('commercio_commercant_hours')
            ->orderBy('commercio_commercant_hours.day', 'ASC');
    }
}
