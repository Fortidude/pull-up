<?php

namespace PullUpBundle\Repository;

use Doctrine\ORM\EntityManager;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;

/**
 * Class AbstractRepository
 * @package PullUpBundle\Repository
 */
abstract class AbstractRepository
{
    /** @var EntityManager */
    protected $em;

    /**
     * AbstractRepository constructor.
     *
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        gc_enable();
        $this->em = $em;
    }

    /**
     * @return EntityManager
     */
    protected function getEntityManager()
    {
        return $this->em;
    }

    /**
     * @return \Doctrine\DBAL\Connection
     */
    protected function getConnection()
    {
        return $this->em->getConnection();
    }

    protected function addEntity($entity)
    {
        $em = $this->getEntityManager();

        try {
            $em->persist($entity);
            $em->flush();
            $em->clear();
            gc_collect_cycles();
        } catch (UniqueConstraintViolationException $e) {
            throw $e;
        }
    }

    public function flush() {
        $this->getEntityManager()->flush();
    }

    protected function removeEntity($entity)
    {
        $em = $this->getEntityManager();
        $em->remove($entity);
        $em->flush();
    }
}
