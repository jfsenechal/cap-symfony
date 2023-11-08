<?php

namespace Cap\Commercio\Repository;

use Cap\Commercio\Doctrine\OrmCrudTrait;
use Cap\Commercio\Entity\CommercioCommercantHoursType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CommercioCommercantHoursType|null find($id, $lockMode = null, $lockVersion = null)
 * @method CommercioCommercantHoursType|null findOneBy(array $criteria, array $orderBy = null)
 * @method CommercioCommercantHoursType[]    findAll()
 * @method CommercioCommercantHoursType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HoursTypeRepository extends ServiceEntityRepository
{
    use OrmCrudTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CommercioCommercantHoursType::class);
    }

    /**
     * @return CommercioCommercantHoursType[]
     */
    public function findAllOrdered(): array
    {
        return $this->createQueryBuilder('cta')
            ->orderBy('cta.name', 'DESC')
            ->getQuery()
            ->getResult();
    }

    private function createQb(): QueryBuilder
    {
        return $this->createQueryBuilder('cta')
            ->leftJoin('cta.commercioCommercant', 'commercioCommercant', 'WITH')
            ->addSelect('commercioCommercant');
    }

}
