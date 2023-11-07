<?php

namespace Cap\Commercio\Repository;

use Cap\Commercio\Doctrine\OrmCrudTrait;
use Cap\Commercio\Entity\AddressType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AddressType|null find($id, $lockMode = null, $lockVersion = null)
 * @method AddressType|null findOneBy(array $criteria, array $orderBy = null)
 * @method AddressType[]    findAll()
 * @method AddressType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AddressTypeRepository extends ServiceEntityRepository
{
    use OrmCrudTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AddressType::class);
    }

    /**
     * @return AddressType[]
     */
    public function findAllOrdered(): array
    {
        return $this->createQueryBuilder('address_type')
            ->orderBy('address_type.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    private function createQb(): QueryBuilder
    {
        return $this->createQueryBuilder('address_type');
    }

}
