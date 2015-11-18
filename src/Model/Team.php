<?php 
// /src/Model/Team.php

namespace Model;

use Doctrine\Common\Collections\ArrayCollection as ArrayCollection;

/**
 * @Entity
 * @Table(name="teams")
 */
class Team {
    
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
     * @ManyToOne(targetEntity="\Model\Organization", inversedBy="teams")
     **/
    private $organization;

    /**
     * @OneToMany(targetEntity="\Model\User", mappedBy="team")
     **/
    private $members;

    /**
     * @OneToMany(targetEntity="\Model\Activity", mappedBy="team")
     **/
    private $activities;
    
    /**
     * @Column(type="integer")
     */
    private $type; // bevers, rtt, verkenners, etc.
    
    
    public function __construct() {
        
    }
    
    public function getId(){
        return $this->id;
    }
    
    public function getName(){
        return $this->name;
    }
    
    public function setName($name){
        $this->name = $name;
    }
    
    public function getOrganization(){
        return $this->organization;
    }
    
    public function setOrganization(\Model\Organization $organization){
        $this->organization = $organization;
    }
    
    public function getMembers(){
        return $this->members;
    }
    
    public function setMembers($members){
        $this->members = $members;
    }
    
}