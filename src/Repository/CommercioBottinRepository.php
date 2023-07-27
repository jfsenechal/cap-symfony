<?php

namespace Cap\Commercio\Repository;

use Cap\Commercio\Doctrine\OrmCrudTrait;
use Cap\Commercio\Entity\CommercioBottin;
use Cap\Commercio\Entity\CommercioCommercant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CommercioBottin|null find($id, $lockMode = null, $lockVersion = null)
 * @method CommercioBottin|null findOneBy(array $criteria, array $orderBy = null)
 * @method CommercioBottin[]    findAll()
 * @method CommercioBottin[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommercioBottinRepository extends ServiceEntityRepository
{
    use OrmCrudTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CommercioBottin::class);
    }

    /**
     * @return CommercioBottin[]
     */
    public function findAllOrdered(): array
    {
        return $this->createQueryBuilder('commercio_bottin')
            ->getQuery()
            ->getResult();
    }

    public function findByCommercerant(CommercioCommercant $commercant): ?CommercioBottin
    {
        return $this->createQueryBuilder('commercio_bottin')
            ->andWhere('commercio_bottin.commercantId = :id')
            ->setParameter('id', $commercant->getId())
            ->getQuery()
            ->getOneOrNullResult();
    }
}
