<?php

namespace Cap\Commercio\Repository;

use Cap\Commercio\Doctrine\OrmCrudTrait;
use Cap\Commercio\Entity\AddressAddress;
use Cap\Commercio\Entity\CommercioCommercant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AddressAddress|null find($id, $lockMode = null, $lockVersion = null)
 * @method AddressAddress|null findOneBy(array $criteria, array $orderBy = null)
 * @method AddressAddress[]    findAll()
 * @method AddressAddress[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AddressRepository extends ServiceEntityRepository
{
    use OrmCrudTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AddressAddress::class);
    }

    /**
     * @return AddressAddress[]
     */
    public function findAllOrdered(): array
    {
        return $this->createQueryBuilder('address')
            ->orderBy('address.city', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findByCommercant(CommercioCommercant $commercant): array
    {
        return $this->createQb()
            ->andWhere('address.commercioCommercant = :shop')
            ->setParameter('shop', $commercant)
            ->getQuery()->getResult();
    }

    private function createQb(): QueryBuilder
    {
        return $this->createQueryBuilder('address')
            ->leftJoin('address.addressType', 'address_type', 'WITH')
            ->leftJoin('address.country', 'country', 'WITH')
            ->addSelect('address_type', 'country');
    }

}
