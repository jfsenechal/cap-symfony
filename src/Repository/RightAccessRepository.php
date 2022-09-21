<?php

namespace Cap\Commercio\Repository;

use Cap\Commercio\Doctrine\OrmCrudTrait;
use Cap\Commercio\Entity\RightAccess;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RightAccess|null find($id, $lockMode = null, $lockVersion = null)
 * @method RightAccess|null findOneBy(array $criteria, array $orderBy = null)
 * @method RightAccess[]    findAll()
 * @method RightAccess[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RightAccessRepository extends ServiceEntityRepository
{
    use OrmCrudTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RightAccess::class);
    }

}
