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
class ChecklistItem implements \JsonSerializable
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
    private $position;

    /**
     * Checklist that this item belongs to.
     *
     * @ManyToOne(targetEntity="\Model\Activity\Checklist\Checklist", inversedBy="items")
     * @var \Model\Activity\Checklist\Checklist
     */
    private $checklist;

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
            'description' => $this->description,
        ];
    }}