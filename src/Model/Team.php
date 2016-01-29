<?php
// /src/Model/Team.php

namespace Model;

use Doctrine\Common\Collections\ArrayCollection as ArrayCollection;

/**
 * Model for Team.
 *
 * Team is a group of users.
 *
 * @Entity
 * @Table(name="teams")
 * @author Geert Derks <geertderks12@gmail.com>
 */
class Team
{
    
    /**
     * Primary key for the team.
     *
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     * @var int
     */
    private $id;
    
    /**
     * Natural name of the team.
     *
     * @Column(type="string")
     * @var string
     */
    private $name;

    /**
     * Organization that this team belongs to.
     *
     * @ManyToOne(targetEntity="\Model\Organization", inversedBy="teams")
     * @var \Model\Organization
     */
    private $organization;

    /**
     * Users that belong to this team.
     *
     * @OneToMany(targetEntity="\Model\User", mappedBy="team")
     * @var null|\Model\User[]
     */
    private $members;

    /**
     * Activities that are owned by members of this team.
     *
     * @OneToMany(targetEntity="\Model\Activity\Activity", mappedBy="team")
     * @var null|\Model\Activity\Activity[]
     */
    private $activities;

    /**
     * Type of this team.
     *
     * @Column(type="string")
     * @var \Model\Enum\GroupType
     */
    private $type;
    
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
     * @return \Model\Organization
     */
    public function getOrganization() {
        return $this->organization;
    }
    
    /**
     * @param \Model\Organization $organization
     */
    public function setOrganization(Organization $organization) {
        $this->organization = $organization;
    }
    
    /**
     * @return null|\Model\User[]
     */
    public function getMembers() {
        return $this->members;
    }
    
    /**
     * @return null|\Model\Activity\Activity[]
     */
    public function getActivities() {
        return $this->activities;
    }

    /**
     * @return \Model\Enum\GroupType
     */
    public function getType() {
        return new Enum\GroupType($this->type);
    }

    /**
     * @param \Model\Enum\GroupType $type
     */
    public function setType(Enum\GroupType $type) {
        $this->type = $type->value();
    }
    
}