<?php

namespace Cap\Commercio\Repository;

use Cap\Commercio\Doctrine\OrmCrudTrait;
use Cap\Commercio\Entity\CommercioCommercant;
use Cap\Commercio\Entity\RightAccess;
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

    public function findByRightAccess(RightAccess $rightAccess): ?CommercioCommercant
    {
        return $this->createQb()
            ->andWhere('commercant.rightAccess = :right')
            ->setParameter('right', $rightAccess)
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
    public function findCanReceiveNews(): array
    {
        return $this->createQb()
            ->andWhere('commercant.canReceiveNews = :receive')
            ->setParameter('receive', 1)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return CommercioCommercant[]
     */
    public function findMemberAndCanReceiveNews(): array
    {
        return $this->createQb()
            ->andWhere('commercant.isMember = :member')
            ->setParameter('member', 1)
            ->andWhere('commercant.canReceiveNews = :receive')
            ->setParameter('receive', 1)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return CommercioCommercant[]
     */
    public function findMembers(): array
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

        if ($name) {
            $qb->andWhere('upper(commercant.legalEntity) LIKE upper(:name)')
                ->setParameter('name', '%'.$name.'%');
        }

        if ($isMember !== null) {
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
