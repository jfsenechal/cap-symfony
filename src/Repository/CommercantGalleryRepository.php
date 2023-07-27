<?php

namespace Cap\Commercio\Repository;

use Cap\Commercio\Doctrine\OrmCrudTrait;
use Cap\Commercio\Entity\CommercioCommercant;
use Cap\Commercio\Entity\CommercioCommercantGallery;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CommercioCommercantGallery|null find($id, $lockMode = null, $lockVersion = null)
 * @method CommercioCommercantGallery|null findOneBy(array $criteria, array $orderBy = null)
 * @method CommercioCommercantGallery[]    findAll()
 * @method CommercioCommercantGallery[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommercantGalleryRepository extends ServiceEntityRepository
{
    use OrmCrudTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CommercioCommercantGallery::class);
    }

    /**
     * @return CommercioCommercantGallery[]
     */
    public function findByCommercant(CommercioCommercant $commercant): array
    {
        return $this->createQb()
            ->andWhere('commercant_gallery.commercioCommercant = :commercant')
            ->setParameter('commercant', $commercant)
            ->getQuery()
            ->getResult();
    }

    private function createQb(): QueryBuilder
    {
        return $this->createQueryBuilder('commercant_gallery')
            ->orderBy('commercant_gallery.name', 'ASC');
    }
}
