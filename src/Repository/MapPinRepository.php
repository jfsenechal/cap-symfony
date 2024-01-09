<?php

namespace Cap\Commercio\Repository;

use Cap\Commercio\Doctrine\OrmCrudTrait;
use Cap\Commercio\Entity\MapPins;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MapPins|null find($id, $lockMode = null, $lockVersion = null)
 * @method MapPins|null findOneBy(array $criteria, array $orderBy = null)
 * @method MapPins[]    findAll()
 * @method MapPins[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MapPinRepository extends ServiceEntityRepository
{
    use OrmCrudTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MapPins::class);
    }

    /**
     * @return MapPins[]
     */
    public function findAllOrdered(): array
    {
        return $this->createQb()
            ->orderBy('map_pin.city', 'ASC')
            ->getQuery()
            ->getResult();
    }

    private function createQb(): QueryBuilder
    {
        return $this->createQueryBuilder('map_pin')
            ->leftJoin('map_pin.pinType', 'pinType', 'WITH')
            ->addSelect('pinType');
    }

}
