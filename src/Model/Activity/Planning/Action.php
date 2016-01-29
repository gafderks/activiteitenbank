<?php
// /src/Model/Activity/Planning/Action.php

namespace Model\Activity\Planning;

/**
 * Model for Planning Action.
 *
 * @Entity
 * @Table(name="planning_actions")
 * @author Geert Derks <geertderks12@gmail.com>
 */
class Action
{

    /**
     * Primary key for the action.
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
     * Planning that this action belongs to.
     *
     * @ManyToOne(targetEntity="\Model\Activity\Planning\Planning", inversedBy="actions")
     * @var \Model\Activity\Planning\Planning
     */
    private $planning;

    /**
     * Timespan that this action takes in minutes.
     *
     * @Column(type="integer")
     * @var int
     */
    private $timeSpan;

    /**
     * Description for this action.
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
     * @return \Model\Activity\Planning\Planning
     */
    public function getPlanning() {
        return $this->planning;
    }

    /**
     * @param \Model\Activity\Planning\Planning $planning
     */
    public function setPlanning(Planning $planning) {
        $this->planning = $planning;
    }

    /**
     * @return int
     */
    public function getTimeSpan() {
        return $this->timeSpan;
    }

    /**
     * @param int $timeSpan
     */
    public function setTimeSpan($timeSpan) {
        $this->timeSpan = $timeSpan;
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