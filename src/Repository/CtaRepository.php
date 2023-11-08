<?php

namespace Cap\Commercio\Repository;

use Cap\Commercio\Doctrine\OrmCrudTrait;
use Cap\Commercio\Entity\CommercioCommercant;
use Cap\Commercio\Entity\CommercioCta;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CommercioCta|null find($id, $lockMode = null, $lockVersion = null)
 * @method CommercioCta|null findOneBy(array $criteria, array $orderBy = null)
 * @method CommercioCta[]    findAll()
 * @method CommercioCta[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CtaRepository extends ServiceEntityRepository
{
    use OrmCrudTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CommercioCta::class);
    }

    /**
     * @return CommercioCta[]
     */
    public function findAllOrdered(): array
    {
        return $this->createQueryBuilder('cta')
            ->orderBy('cta.name', 'DESC')
            ->getQuery()
            ->getResult();
    }

    private function createQb(): QueryBuilder
    {
        return $this->createQueryBuilder('cta')
            ->leftJoin('cta.commercioCommercant', 'commercioCommercant', 'WITH')
            ->addSelect('commercioCommercant');
    }

}
