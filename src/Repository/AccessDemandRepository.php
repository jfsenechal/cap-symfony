<?php

namespace Cap\Commercio\Repository;

use Cap\Commercio\Doctrine\OrmCrudTrait;
use Cap\Commercio\Entity\AccessDemand;
use Cap\Commercio\Entity\RightAccess;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AccessDemand|null find($id, $lockMode = null, $lockVersion = null)
 * @method AccessDemand|null findOneBy(array $criteria, array $orderBy = null)
 * @method AccessDemand[]    findAll()
 * @method AccessDemand[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AccessDemandRepository extends ServiceEntityRepository
{
    use OrmCrudTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AccessDemand::class);
    }

    public function findByRightAccess(RightAccess $rightAccess): ?AccessDemand
    {
        return $this->createQb()
            ->andWhere('access_demand.rightAccess = :right')
            ->setParameter('right', $rightAccess)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @return array|AccessDemand
     */
    public function findAllOrdered(): array
    {
        return $this->createQb()->getQuery()->getResult();
    }

    private function createQb(): QueryBuilder
    {
        return $this->createQueryBuilder('access_demand')
            ->orderBy('access_demand.insertDate', 'DESC');
    }
}
