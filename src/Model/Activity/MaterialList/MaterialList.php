<?php
// /src/Model/Activity/Materials/MaterialList.php

namespace Model\Activity\MaterialList;

/**
 * Model for Material list.
 *
 * @Entity
 * @Table(name="materiallists")
 * @author Geert Derks <geertderks12@gmail.com>
 */
class MaterialList
{

    /**
     * Primary key for material list.
     *
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     * @var int
     */
    private $id;

    /**
     * Materials that belong to this material list.
     *
     * @OneToMany(targetEntity="\Model\Activity\MaterialList\MaterialListItem", mappedBy="materialList")
     * @OrderBy({"order" = "ASC"})
     * @var null|\Model\Activity\MaterialList\MaterialListItem
     */
    private $materials;

    /**
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @return null|\Model\Activity\MaterialList\MaterialListItem
     */
    public function getMaterials() {
        return $this->materials;
    }

}