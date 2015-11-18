<?php
// /src/Model/Activity/Checklist/Checklist.php

namespace Model\Activity\Checklist;

/**
 * @Entity
 * @Table(name="checklists")
 */
class Checklist {

    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     */
    private $id;

    /**
     * @OneToMany(targetEntity="\Model\Activity\Checklist\ChecklistItem", mappedBy="checklist")
     * @OrderBy({"order" = "ASC"})
     */
    private $items;

}