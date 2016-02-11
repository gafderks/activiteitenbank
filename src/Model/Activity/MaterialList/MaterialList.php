<?php
// /src/Model/Activity/Materials/MaterialList.php

namespace Model\Activity\MaterialList;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Model for Material list.
 *
 * @Entity
 * @Table(name="materiallists")
 * @author Geert Derks <geertderks12@gmail.com>
 */
class MaterialList implements \JsonSerializable
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
     * @OrderBy({"position" = "ASC"})
     * @var null|\Model\Activity\MaterialList\MaterialListItem
     */
    private $materials;

    /**
     * MaterialList constructor.
     */
    public function __construct() {
        $this->materials = new ArrayCollection();
    }

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
            'materials' => $this->materials->toArray(),
        ];
    }}