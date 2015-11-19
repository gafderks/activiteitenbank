<?php
// /src/Model/Activity/MaterialList/MaterialListItem.php

namespace Model\Activity\MaterialList;

/**
 * @Entity
 * @Table(name="materiallist_items")
 */
class MaterialListItem {

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
     * @Column(type="float")
     */
    private $amount;

    /**
     * @ManyToOne(targetEntity="\Model\Activity\MaterialList\MaterialList", inversedBy="materials")
     */
    private $materialList;

    /**
     * @Column(type="string")
     */
    private $description;

}