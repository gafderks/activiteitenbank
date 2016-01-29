<?php
// /src/Model/Organization.php

namespace Model;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Model for Organization.
 *
 * Organization is a group of teams.
 *
 * @Entity
 * @Table(name="organizations")
 * @author Geert Derks <geertderks12@gmail.com>
 */
class Organization
{
    
    /**
     * Primary key for the organization.
     *
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     * @var int
     */
    private $id;
    
    /**
     * Natural name of the organization.
     *
     * @Column(type="string")
     * @var string
     */
    private $name;

    /**
     * Teams that belong to this organization.
     *
     * @OneToMany(targetEntity="\Model\Team", mappedBy="organization")
     * @var null|\Model\Team[]
     */
    private $teams;

    /**
     * Activities that are owned by users from teams of this organizations.
     *
     * @OneToMany(targetEntity="\Model\Activity\Activity", mappedBy="organization")
     * @var null|\Model\Activity\Activity[]
     */
    private $activities;

    /**
     * Categories that are used by this organization.
     *
     * @OneToMany(targetEntity="\Model\Activity\Category", mappedBy="organization")
     * @var null|\Model\Activity\Category[]
     */
    private $categories;

    /**
     * @return null|\Model\Activity\Activity[]
     */
    public function getActivities() {
        return $this->activities;
    }

    /**
     * @return null|\Model\Activity\Category[]
     */
    public function getCategories() {
        return $this->categories;
    }
    
    /**
     * @return int
     */
    public function getId() {
        return $this->id;
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
     * @return null|\Model\Team[]
     */
    public function getTeams() {
        return $this->teams;
    }
    
}