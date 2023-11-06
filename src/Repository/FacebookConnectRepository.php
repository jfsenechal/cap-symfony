<?php

namespace Cap\Commercio\Repository;

use Cap\Commercio\Doctrine\OrmCrudTrait;
use Cap\Commercio\Entity\CommercioCommercant;
use Cap\Commercio\Entity\FacebookConnect;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method FacebookConnect|null find($id, $lockMode = null, $lockVersion = null)
 * @method FacebookConnect|null findOneBy(array $criteria, array $orderBy = null)
 * @method FacebookConnect[]    findAll()
 * @method FacebookConnect[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FacebookConnectRepository extends ServiceEntityRepository
{
    use OrmCrudTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FacebookConnect::class);
    }

    public function findByCommercant(CommercioCommercant $commercant): array
    {
        return $this->createQb()
            ->andWhere('facebook_connect.commercant = :shop')
            ->setParameter('shop', $commercant)
            ->getQuery()->getResult();
    }

    private function createQb(): QueryBuilder
    {
        return $this->createQueryBuilder('facebook_connect')
            ->leftJoin('facebook_connect.commercant', 'commercant', 'WITH')
            ->addSelect('commercant');
    }

}
