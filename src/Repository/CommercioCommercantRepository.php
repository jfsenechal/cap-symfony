<?php

namespace Cap\Commercio\Repository;

use Cap\Commercio\Doctrine\OrmCrudTrait;
use Cap\Commercio\Entity\CommercioCommercant;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CommercioCommercant|null find($id, $lockMode = null, $lockVersion = null)
 * @method CommercioCommercant|null findOneBy(array $criteria, array $orderBy = null)
 * @method CommercioCommercant[]    findAll()
 * @method CommercioCommercant[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommercioCommercantRepository extends ServiceEntityRepository
{
    use OrmCrudTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CommercioCommercant::class);
    }


    public function findByIdCommercant(int $id): ?CommercioCommercant
    {
        return $this->createQb()
            ->andWhere('commercant.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @return CommercioCommercant[]
     */
    public function findAllOrdered(): array
    {
        return $this->createQb()
            ->getQuery()
            ->getResult();
    }

    /**
     * @return CommercioCommercant[]
     */
    public function membres(): array
    {
        return $this->createQb()
            ->andWhere('commercant.isMember = :member')
            ->setParameter('member', 1)
            ->orderBy('commercant.affiliationDate', 'DESC')
            ->getQuery()->getResult();
    }

    /**
     * @return CommercioCommercant[]
     */
    public function search(?string $name, ?int $isMember = null): array
    {
        $qb = $this->createQb();
        $qb->andWhere('upper(commercant.legalEntity) LIKE upper(:name)')
            ->setParameter('name', '%' . $name . '%');

        if ($isMember === 1 || $isMember === 0) {
            $qb->andWhere('commercant.isMember = :member')
                ->setParameter('member', $isMember);
        }

        return $qb->getQuery()->getResult();
    }

    /**
     * @return CommercioCommercant[]
     */
    public function findExpired(DateTime $today): array
    {
        return $this->createQb()
            ->andWhere('commercant.affiliationDate < :date')
            ->setParameter('date', $today->format('Y-m-d'))
            ->andWhere('commercant.isMember = :member')
            ->setParameter('member', true)
            ->orderBy('commercant.affiliationDate', 'DESC')
            ->getQuery()->getResult();
    }

    private function createQb(): QueryBuilder
    {
        return $this->createQueryBuilder('commercant')
            ->orderBy('commercant.legalEntity', 'ASC');
    }
}
