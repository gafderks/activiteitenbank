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
class Action implements \JsonSerializable
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
    private $position;

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
    public function getPosition() {
        return $this->position;
    }

    /**
     * @param int $position
     */
    public function setPosition($position) {
        $this->position = $position;
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


    /**
     * Specify data which should be serialized to JSON
     *
     * @link  http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     *        which is a value of any type other than a resource.
     * @since 5.4.0
     */
    function jsonSerialize() {
        return [
            'id' => $this->id,
            'position' => $this->position,
            'duration' => $this->timeSpan,
            'description' => $this->description,
        ];
    }}