<?php

namespace Cap\Commercio\Repository;

use Cap\Commercio\Doctrine\OrmCrudTrait;
use Cap\Commercio\Entity\AddressIsoCountries;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AddressIsoCountries|null find($id, $lockMode = null, $lockVersion = null)
 * @method AddressIsoCountries|null findOneBy(array $criteria, array $orderBy = null)
 * @method AddressIsoCountries[]    findAll()
 * @method AddressIsoCountries[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AddressIsoCountriesRepository extends ServiceEntityRepository
{
    use OrmCrudTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AddressIsoCountries::class);
    }

    /**
     * @return AddressIsoCountries[]
     */
    public function findAllOrdered(): array
    {
        return $this->createQueryBuilder('iso_countries')
            ->orderBy('iso_countries.frFr', 'ASC')
            ->getQuery()
            ->getResult();
    }

    private function createQb(): QueryBuilder
    {
        return $this->createQueryBuilder('iso_countries');
    }

}
