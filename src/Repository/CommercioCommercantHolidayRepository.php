<?php

namespace Cap\Commercio\Repository;

use Cap\Commercio\Doctrine\OrmCrudTrait;
use Cap\Commercio\Entity\CommercioCommercant;
use Cap\Commercio\Entity\CommercioCommercantHoliday;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CommercioCommercantHoliday|null find($id, $lockMode = null, $lockVersion = null)
 * @method CommercioCommercantHoliday|null findOneBy(array $criteria, array $orderBy = null)
 * @method CommercioCommercantHoliday[]    findAll()
 * @method CommercioCommercantHoliday[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommercioCommercantHolidayRepository extends ServiceEntityRepository
{
    use OrmCrudTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CommercioCommercantHoliday::class);
    }

    /**
     * @return CommercioCommercantHoliday[]
     */
    public function findAllOrdered(): array
    {
        return $this->createQb()
            ->getQuery()
            ->getResult();
    }

    /**
     * @return CommercioCommercantHoliday[]
     */
    public function findByCommercerant(CommercioCommercant $commercioCommercant): array
    {
        return $this->createQb()
            ->andWhere('commercio_commercant_holiday.commercioCommercant = :commercant')
            ->setParameter('commercant', $commercioCommercant)
            ->getQuery()->getResult();
    }

    private function createQb(): QueryBuilder
    {
        return $this->createQueryBuilder('commercio_commercant_holiday')
            ->orderBy('commercio_commercant_holiday.beginDate', 'ASC');
    }

}
