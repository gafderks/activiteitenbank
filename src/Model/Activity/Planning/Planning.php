<?php
// /src/Model/Activity/Planning/Planning.php

namespace Model\Activity\Planning;

/**
 * @Entity
 * @Table(name="plannings")
 */
class Planning {

    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     */
    private $id;

    /**
     * @OneToMany(targetEntity="\Model\Activity\Planning\Action", mappedBy="planning")
     * @OrderBy({"order" = "ASC"})
     */
    private $actions;

}