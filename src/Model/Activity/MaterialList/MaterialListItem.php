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
class MaterialListItem
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
    private $order;

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


}