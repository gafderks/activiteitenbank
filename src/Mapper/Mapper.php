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

    protected $app;

    /**
     * Constructor
     *
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
        $this->app = \Slim\Slim::getInstance();
    }

    /**
     * Flush.
     */
    public function flush()
    {
        $this->em->flush();
    }

    /**
     * Get the repository for this mapper.
     *
     * @return \Doctrine\ORM\EntityRepository
     */
    abstract public function getRepository();

}