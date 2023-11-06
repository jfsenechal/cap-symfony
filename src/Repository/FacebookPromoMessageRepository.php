<?php

namespace Cap\Commercio\Repository;

use Cap\Commercio\Doctrine\OrmCrudTrait;
use Cap\Commercio\Entity\CommercioCommercant;
use Cap\Commercio\Entity\FacebookPromoMessage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method FacebookPromoMessage|null find($id, $lockMode = null, $lockVersion = null)
 * @method FacebookPromoMessage|null findOneBy(array $criteria, array $orderBy = null)
 * @method FacebookPromoMessage[]    findAll()
 * @method FacebookPromoMessage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FacebookPromoMessageRepository extends ServiceEntityRepository
{
    use OrmCrudTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FacebookPromoMessage::class);
    }

    public function findByCommercant(CommercioCommercant $commercant): array
    {
        return $this->createQb()
            ->andWhere('facebook_promo_message.commercant = :shop')
            ->setParameter('shop', $commercant)
            ->getQuery()->getResult();
    }

    private function createQb(): QueryBuilder
    {
        return $this->createQueryBuilder('facebook_promo_message')
            ->leftJoin('facebook_promo_message.commercant', 'commercant', 'WITH')
            ->addSelect('commercant');
    }

}
