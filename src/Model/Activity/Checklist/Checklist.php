<?php
// /src/Model/Activity/Checklist/Checklist.php

namespace Model\Activity\Checklist;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Model for Checklist.
 *
 * @Entity
 * @Table(name="checklists")
 * @author Geert Derks <geertderks12@gmail.com>
 */
class Checklist implements \JsonSerializable
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
     * @OrderBy({"position" = "ASC"})
     * @var null|\Model\Activity\Checklist\ChecklistItem[]
     */
    private $items;

    /**
     * Checklist constructor.
     */
    public function __construct() {
        $this->items = new ArrayCollection();
    }

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
            'items' => $this->items->toArray(),
        ];
    }}