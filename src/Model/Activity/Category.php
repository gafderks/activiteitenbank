<?php
// /src/Model/Activity/Category.php

namespace Model\Activity;

use Doctrine\Common\Collections\ArrayCollection as ArrayCollection;

/**
 * Model for Activity Category.
 *
 * @Entity
 * @Table(name="categories")
 * @author Geert Derks <geertderks12@gmail.com>
 */
class Category implements \JsonSerializable
{

    /**
     * Primary key for the category.
     *
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     * @var int
     */
    private $id;

    /**
     * Natural name for the category.
     *
     * @Column(type="string")
     * @var string
     */
    private $name;

    /**
     * Organization that the category belongs to.
     *
     * @ManyToOne(targetEntity="\Model\Organization", inversedBy="categories")
     * @var \Model\Organization
     */
    private $organization;

    /**
     * Activities that belong to this category.
     *
     * @ManyToMany(targetEntity="Activity", mappedBy="categories")
     * @var \Model\Activity\Activity[]
     */
    private $activities;

    /**
     * Category constructor.
     */
    public function __construct() {
        $this->activities = new ArrayCollection();
    }

    /**
     * @return Activity[]
     */
    public function getActivities() {
        return $this->activities;
    }

    /**
     * @param Activity[] $activities
     */
    public function setActivities(ArrayCollection $activities) {
        $this->activities = $activities;
    }

    /**
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id) {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name) {
        $this->name = $name;
    }

    /**
     * @return \Model\Organization
     */
    public function getOrganization() {
        return $this->organization;
    }

    /**
     * @param \Model\Organization $organization
     */
    public function setOrganization(\Model\Organization $organization) {
        $this->organization = $organization;
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
            'name' => $this->name,
        ];
    }}