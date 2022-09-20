<?php

namespace Cap\Commercio\Doctrine;

use Doctrine\ORM\EntityManagerInterface;

trait OrmCrudTrait
{
    /**
     * @var EntityManagerInterface
     */
    protected $_em;

    public function getEntityManger(): EntityManagerInterface {
        return $this->_em;
    }

    public function insert(object $object): void
    {
        $this->persist($object);
        $this->flush();
    }

    public function persist(object $object): void
    {
        $this->_em->persist($object);
    }

    public function flush(): void
    {
        $this->_em->flush();
    }

    public function remove(object $object): void
    {
        $this->_em->remove($object);
    }

    public function getOriginalEntityData(object $object)
    {
        return $this->_em->getUnitOfWork()->getOriginalEntityData($object);
    }
}