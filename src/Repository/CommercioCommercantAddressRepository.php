<?php

namespace Cap\Commercio\Repository;

use Cap\Commercio\Doctrine\OrmCrudTrait;
use Cap\Commercio\Entity\CommercioCommercant;
use Cap\Commercio\Entity\CommercioCommercantAddress;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CommercioCommercantAddress|null find($id, $lockMode = null, $lockVersion = null)
 * @method CommercioCommercantAddress|null findOneBy(array $criteria, array $orderBy = null)
 * @method CommercioCommercantAddress[]    findAll()
 * @method CommercioCommercantAddress[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommercioCommercantAddressRepository extends ServiceEntityRepository
{
    use OrmCrudTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CommercioCommercantAddress::class);
    }

    public function findByCommercant(CommercioCommercant $commercant): array
    {
        return $this->createQb()
            ->andWhere('commercio_commercant_address.commercioCommercant = :shop')
            ->setParameter('shop', $commercant)
            ->getQuery()->getResult();
    }

    private function createQb(): QueryBuilder
    {
        return $this->createQueryBuilder('commercio_commercant_address')
            ->leftJoin('commercio_commercant_address.commercioCommercant', 'commercioCommercant', 'WITH')
            ->leftJoin('commercio_commercant_address.address', 'address', 'WITH')
            ->addSelect('commercioCommercant', 'address');
    }
}
