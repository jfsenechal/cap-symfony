<?php

namespace Cap\Commercio\Repository;

use Cap\Commercio\Doctrine\OrmCrudTrait;
use Cap\Commercio\Entity\CommercioCommercant;
use Cap\Commercio\Entity\CommercioPixel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CommercioPixel|null find($id, $lockMode = null, $lockVersion = null)
 * @method CommercioPixel|null findOneBy(array $criteria, array $orderBy = null)
 * @method CommercioPixel[]    findAll()
 * @method CommercioPixel[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PixelRepository extends ServiceEntityRepository
{
    use OrmCrudTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CommercioPixel::class);
    }

    public function findByCommercant(CommercioCommercant $commercant): array
    {
        return $this->createQb()
            ->andWhere('pixel.commercioCommercant = :shop')
            ->setParameter('shop', $commercant)
            ->getQuery()->getResult();
    }

    private function createQb(): QueryBuilder
    {
        return $this->createQueryBuilder('pixel')
            ->leftJoin('pixel.commercioCommercant', 'commercioCommercant', 'WITH')
            ->addSelect('commercioCommercant');
    }

}
