<?php
// /src/Model/Activity/Category.php

namespace Model\Activity;

/**
 * @Entity
 * @Table(name="categories")
 */
class Category {

    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     */
    private $id;

    /**
     * @Column(type="string")
     */
    private $name;

    /**
     * @ManyToOne(targetEntity="\Model\Organization", inversedBy="categories")
     **/
    private $organization;

    /**
     * @return mixed
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id) {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name) {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getOrganization() {
        return $this->organization;
    }

    /**
     * @param mixed $organization
     */
    public function setOrganization($organization) {
        $this->organization = $organization;
    }

}