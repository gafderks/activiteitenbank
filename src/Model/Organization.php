<?php 
// /src/Model/Organization.php

namespace Model;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity
 * @Table(name="organizations")
 */
class Organization {
    
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
     * @OneToMany(targetEntity="\Model\Team", mappedBy="organization")
     **/
    private $teams;

    /**
     * @OneToMany(targetEntity="\Model\Activity", mappedBy="organization")
     **/
    private $activities;
    
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
    
    public function getTeams(){
        return $this->teams;
    }
    
}