<?php
// /src/Model/Activity/Checklist/ChecklistItem.php

namespace Model\Activity\Checklist;

/**
 * Model for Checklist item.
 *
 * @Entity
 * @Table(name="checklist_items")
 * @author Geert Derks <geertderks12@gmail.com>
 */
class ChecklistItem
{

    /**
     * Primary key for the item.
     *
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     * @var int
     */
    private $id;

    /**
     * Ordering constant. Order is ascending on this value.
     *
     * @Column(type="integer")
     * @var int
     */
    private $order;

    /**
     * Checklist that this item belongs to.
     *
     * @ManyToOne(targetEntity="\Model\Activity\Checklist\Checklist", inversedBy="items")
     * @var \Model\Activity\Checklist\Checklist
     */
    private $checklist;

    /**
     * Completion status of this checklist item.
     *
     * @Column(type="boolean")
     * @var bool
     */
    private $completed;

    /**
     * Description of this item.
     *
     * @Column(type="string")
     * @var string
     */
    private $description;

    /**
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getOrder() {
        return $this->order;
    }

    /**
     * @param int $order
     */
    public function setOrder($order) {
        $this->order = $order;
    }

    /**
     * @return \Model\Activity\Checklist\Checklist
     */
    public function getChecklist() {
        return $this->checklist;
    }

    /**
     * @param \Model\Activity\Checklist\Checklist $checklist
     */
    public function setChecklist(Checklist $checklist) {
        $this->checklist = $checklist;
    }

    /**
     * @return bool
     */
    public function getCompleted() {
        return $this->completed;
    }

    /**
     * @param bool $completed
     */
    public function setCompleted($completed) {
        $this->completed = $completed;
    }

    /**
     * @return string
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description) {
        $this->description = $description;
    }

}