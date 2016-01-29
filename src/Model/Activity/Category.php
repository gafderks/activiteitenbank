<?php
// /src/Model/Activity/Category.php

namespace Model\Activity;

/**
 * Model for Activity Category.
 *
 * @Entity
 * @Table(name="categories")
 * @author Geert Derks <geertderks12@gmail.com>
 */
class Category
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

}