<?php

namespace Cap\Commercio\Repository;

use Cap\Commercio\Doctrine\OrmCrudTrait;
use Cap\Commercio\Entity\AddressAddress;
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

    /**
     * @return CommercioCommercantAddress[]
     */
    public function findAllOrdered(): array
    {
        return $this->createQueryBuilder('commercant_address')
            ->orderBy('commercant_address.commercioCommercant', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findOneByCommercant(CommercioCommercant $commercant): ?CommercioCommercantAddress
    {
        return $this->createQb()
            ->andWhere('commercant_address.commercioCommercant = :shop')
            ->setParameter('shop', $commercant)
            ->getQuery()->getOneOrNullResult();
    }

    public function findOneByAddress(AddressAddress $address): ?CommercioCommercantAddress
    {
        return $this->createQb()
            ->andWhere('commercant_address.address = :address')
            ->setParameter('address', $address)
            ->getQuery()->getOneOrNullResult();
    }

    private function createQb(): QueryBuilder
    {
        return $this->createQueryBuilder('commercant_address')
            ->leftJoin('commercant_address.commercioCommercant', 'commercioCommercant', 'WITH')
            ->leftJoin('commercant_address.address', 'address', 'WITH')
            ->addSelect('commercioCommercant', 'address');
    }

}
