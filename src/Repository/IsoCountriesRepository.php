<?php

namespace Cap\Commercio\Repository;

use Cap\Commercio\Doctrine\OrmCrudTrait;
use Cap\Commercio\Entity\IsoCountries;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method IsoCountries|null find($id, $lockMode = null, $lockVersion = null)
 * @method IsoCountries|null findOneBy(array $criteria, array $orderBy = null)
 * @method IsoCountries[]    findAll()
 * @method IsoCountries[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IsoCountriesRepository extends ServiceEntityRepository
{
    use OrmCrudTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, IsoCountries::class);
    }

    /**
     * @return IsoCountries[]
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
