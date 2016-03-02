<?php
// /src/Mapper/Mapper.php

namespace Mapper;

use Doctrine\ORM\EntityManager;


abstract class Mapper
{

    /**
     * Doctrine entity manager.
     *
     * @var EntityManager
     */
    protected $em;

    protected $container;

    /**
     * Constructor
     *
     * @param EntityManager $em
     * @param TODO $container
     */
    public function __construct($container, EntityManager $em) {
        $this->em = $em;
        $this->container = $container;
    }

    /**
     * Flush.
     */
    public function flush() {
        $this->em->flush();
    }

    /**
     * Clear.
     */
    public function clear() {
        $this->em->clear();
    }

    /**
     * Get the repository for this mapper.
     *
     * @return \Doctrine\ORM\EntityRepository
     */
    abstract public function getRepository();

}