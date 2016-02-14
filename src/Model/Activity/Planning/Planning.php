<?php
// /src/Model/Activity/Planning/Planning.php

namespace Model\Activity\Planning;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Model for Planning.
 *
 * @Entity
 * @Table(name="plannings")
 * @author Geert Derks <geertderks12@gmail.com>
 */
class Planning implements \JsonSerializable
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
     * @OrderBy({"position" = "ASC"})
     * @var null|Action[]
     */
    private $actions;

    /**
     * Planning constructor.
     */
    public function __construct() {
        $this->actions = new ArrayCollection();
    }

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

    /**
     * Returns the total duration of the activity.
     *
     * @return int
     */
    public function getTotalDuration() {
        $time = 0;
        foreach($this->actions as $action) {
            $time += $action->getTimeSpan();
        }
        return $time;
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
            'actions' => $this->actions->toArray()
        ];
    }}