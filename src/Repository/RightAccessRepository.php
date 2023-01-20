<?php

namespace Cap\Commercio\Repository;

use Cap\Commercio\Doctrine\OrmCrudTrait;
use Cap\Commercio\Entity\RightAccess;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
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

    public function search(string $name)
    {
        $qb = $this->createQb();

        if ($name) {
            $qb->andWhere('rightAccess.email LIKE :name')
                ->setParameter('name', '%'.$name.'%');
        }

        return $qb->getQuery()->getResult();
    }

    private function createQb(): QueryBuilder
    {
        return $this->createQueryBuilder('rightAccess')
            ->orderBy('rightAccess.email', 'ASC');
    }

}
