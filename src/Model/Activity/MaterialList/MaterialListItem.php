<?php
// /src/Model/Activity/MaterialList/MaterialListItem.php

namespace Model\Activity\MaterialList;

/**
 * Model for Material list Item.
 *
 * @Entity
 * @Table(name="materiallist_items")
 * @author Geert Derks <geertderks12@gmail.com>
 */
class MaterialListItem implements \JsonSerializable
{

    /**
     * Primary key for the material list item.
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
     * Amount of the item.
     *
     * @Column(type="float")
     * @var float
     */
    private $amount;

    /**
     * Material list that this item belongs to.
     *
     * @ManyToOne(targetEntity="\Model\Activity\MaterialList\MaterialList", inversedBy="materials")
     * @var \Model\Activity\MaterialList\MaterialList
     */
    private $materialList;

    /**
     * Description for this material list item.
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
     * @return float
     */
    public function getAmount() {
        return $this->amount;
    }

    /**
     * @param float $amount
     */
    public function setAmount($amount) {
        $this->amount = $amount;
    }

    /**
     * @return \Model\Activity\MaterialList\MaterialList
     */
    public function getMaterialList() {
        return $this->materialList;
    }

    /**
     * @param \Model\Activity\MaterialList\MaterialList $materialList
     */
    public function setMaterialList(MaterialList $materialList) {
        $this->materialList = $materialList;
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
            'amount' => $this->amount,
            'description' => $this->description,
        ];
    }}