<?php

namespace Cap\Commercio\Repository;

use Cap\Commercio\Doctrine\OrmCrudTrait;
use Cap\Commercio\Entity\Contact;
use Cap\Commercio\Entity\ContactParams;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ContactParams|null find($id, $lockMode = null, $lockVersion = null)
 * @method ContactParams|null findOneBy(array $criteria, array $orderBy = null)
 * @method ContactParams[]    findAll()
 * @method ContactParams[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContactParamsRepository extends ServiceEntityRepository
{
    use OrmCrudTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ContactParams::class);
    }

    /**
     * @return array|ContactParams[]
     */
    public function findAllOrdered(): array
    {
        return $this->createQb()
            ->getQuery()
            ->getResult();
    }

    private function createQb(): QueryBuilder
    {
        return $this->createQueryBuilder('contact_params')
            ->orderBy('contact_params.paramKey', 'ASC');
    }

    public function findByContact(Contact $contact): array
    {
        return $this->createQb()
            ->andWhere('contact_params.contact = :contact')
            ->setParameter('contact', $contact)
            ->getQuery()
            ->getResult();
    }
}
