<?php 
// /src/Model/User.php

namespace Model;

use Doctrine\Common\Collections\ArrayCollection as ArrayCollection;

/**
 * Model for User.
 *
 * User is an object for users that are logged in. It contains information about their login credentials, preferences,
 * contact details, team and organization membership, and their belongings.
 *
 * @Entity
 * @Table(name="users")
 */
class User {

    /**
     * Primary key for the user.
     *
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     */
    private $id;
    
    /**
     * First name of the user.
     *
     * @Column(type="string")
     */
    private $first_name;

    /**
     * Last name of the user.
     *
     * @Column(type="string")
     */
    private $last_name;
    
    /**
     * Username that is used for login.
     * Primary key for the user.
     *
     * @Column(type="string")
     */
    private $username;
    
    /**
     * Email address for the users.
     * This address is used for login and for sending notifications.
     *
     * @Column(type="string")
     */
    private $email;

    /**
     * Password hash that is used for login. Includes a salt.
     *
     * @Column(type="string")
     */
    private $password;

    /**
     * Date at which the user registered.
     *
     * @Column(type="datetime", columnDefinition="TIMESTAMP DEFAULT CURRENT_TIMESTAMP")
     */
    private $registered;

    /**
     * Team that the user belongs to.
     *
     * @ManyToOne(targetEntity="\Model\Team", inversedBy="members")
     */
    private $team;
    
    /**
     * Role of the user.
     * The role concerns the special permissions for the user.
     *
     * @Column(type="integer")
     */
    private $role;  // 1 - admin

    /**
     * @OneToMany(targetEntity="\Model\Activity\Activity", mappedBy="creator")
     */
    private $activities;

    /**
     * @OneToMany(targetEntity="\Model\Activity\Attachment", mappedBy="creator")
     */
    private $attachments;
    
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