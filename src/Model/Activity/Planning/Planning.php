<?php
// /src/Model/Activity/Planning/Planning.php

namespace Model\Activity\Planning;

/**
 * Model for Planning.
 *
 * @Entity
 * @Table(name="plannings")
 * @author Geert Derks <geertderks12@gmail.com>
 */
class Planning
{

    /**
     * Primary key for the planning.
     *
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     * @var int
     */
    private $id;

    /**
     * Actions that belong to this planning.
     *
     * @OneToMany(targetEntity="\Model\Activity\Planning\Action", mappedBy="planning")
     * @OrderBy({"order" = "ASC"})
     * @var null|Action[]
     */
    private $actions;

    /**
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @return null|\Model\Activity\Planning\Action[]
     */
    public function getActions() {
        return $this->actions;
    }

}