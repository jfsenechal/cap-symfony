<?php

namespace Cap\Commercio\Repository;

use Cap\Commercio\Doctrine\OrmCrudTrait;
use Cap\Commercio\Entity\CommercioCommercant;
use Cap\Commercio\Entity\CommercioCommercantTag;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CommercioCommercantTag|null find($id, $lockMode = null, $lockVersion = null)
 * @method CommercioCommercantTag|null findOneBy(array $criteria, array $orderBy = null)
 * @method CommercioCommercantTag[]    findAll()
 * @method CommercioCommercantTag[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TagRepository extends ServiceEntityRepository
{
    use OrmCrudTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CommercioCommercantTag::class);
    }

    /**
     * @return CommercioCommercantTag[]
     */
    public function findAllOrdered(): array
    {
        return $this->createQueryBuilder('tag')
            ->orderBy('tag.tag', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findByCommercant(CommercioCommercant $commercant): array
    {
        return $this->createQb()
            ->andWhere('tag.commercant = :shop')
            ->setParameter('shop', $commercant)
            ->getQuery()->getResult();
    }

    private function createQb(): QueryBuilder
    {
        return $this->createQueryBuilder('tag')
            ->leftJoin('tag.commercant', 'commercant', 'WITH')
            ->addSelect('commercant');
    }

}
