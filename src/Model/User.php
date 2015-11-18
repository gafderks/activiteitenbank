<?php 
// /src/Model/User.php

namespace Model;

use Doctrine\Common\Collections\ArrayCollection as ArrayCollection;

/**
 * @Entity
 * @Table(name="users")
 */
class User 
{

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
     * @Column(type="string")
     */
    private $username;
    
    /**
     * @Column(type="string")
     */
    private $email;
    
    private $organization;

    private $team;
    
    /**
     * @Column(type="integer")
     */
    private $role;
    
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
    
    public function getUsername(){
        return $this->username;
    }
    
    public function setUsername($username){
        $this->username = $username;
    }
    
    public function getEmail(){
        return $this->email;
    }
    
    public function setEmail($email){
        $this->email = $email;
    }
    
    public function getOrganization(){
        return $this->organization;
    }
    
    public function setOrganization(\Model\Organization $organization){
        $this->organization = $organization;
    }
    
    public function getTeam(){
        return $this->team;
    }
    
    public function setTeam(\Model\Team $team){
        $this->team = $team;
    }
    
    public function getRole(){
        return $this->role;
    }
    
    public function setRole($role){
        $this->role = $role;
    }
}