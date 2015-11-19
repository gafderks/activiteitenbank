<?php
// /src/Model/Activity/Materials/MaterialList.php

namespace Model\Activity\MaterialList;

/**
 * @Entity
 * @Table(name="materiallists")
 */
class MaterialList {

    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     */
    private $id;

    /**
     * @OneToMany(targetEntity="\Model\Activity\MaterialList\MaterialListItem", mappedBy="materialList")
     * @OrderBy({"order" = "ASC"})
     */
    private $materials;

}