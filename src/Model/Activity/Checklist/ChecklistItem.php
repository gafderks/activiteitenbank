<?php
// /src/Model/Activity/Checklist/ChecklistItem.php

namespace Model\Activity\Checklist;

/**
 * @Entity
 * @Table(name="checklistitems")
 */
class ChecklistItem {

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
     * @ManyToOne(targetEntity="\Model\Activity\Checklist\Checklist", inversedBy="items")
     */
    private $checklist;

    /**
     * @Column(type="boolean")
     */
    private $completed;

    /**
     * @Column(type="string")
     */
    private $description;

}