<?php
// /src/Model/Activity/Checklist/Checklist.php

namespace Model\Activity\Checklist;

/**
 * Model for Checklist.
 *
 * @Entity
 * @Table(name="checklists")
 * @author Geert Derks <geertderks12@gmail.com>
 */
class Checklist
{

    /**
     * Primary key for the checklist.
     *
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     * @var int
     */
    private $id;

    /**
     * Items that belong to this checklist.
     *
     * @OneToMany(targetEntity="\Model\Activity\Checklist\ChecklistItem", mappedBy="checklist")
     * @OrderBy({"order" = "ASC"})
     * @var null|\Model\Activity\Checklist\ChecklistItem[]
     */
    private $items;

    /**
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @return null|\Model\Activity\Checklist\ChecklistItem[]
     */
    public function getItems() {
        return $this->items;
    }


}