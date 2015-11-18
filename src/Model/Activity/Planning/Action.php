<?php
// /src/Model/Activity/Planning/Action.php

namespace Model\Activity\Planning;

/**
 * @Entity
 * @Table(name="actions")
 */
class Action {

    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     */
    private $id;

    /**
     * @Column(type="integer")
     */
    private $order;

    /**
     * @ManyToOne(targetEntity="\Model\Activity\Planning\Planning", inversedBy="actions")
     */
    private $planning;

    /**
     * @Column(type="integer")
     */
    private $timeSpan;

    /**
     * @Column(type="string")
     */
    private $description;

}