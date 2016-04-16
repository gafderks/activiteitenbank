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
 * @author Geert Derks <geertderks12@gmail.com>
 */
class User implements \JsonSerializable
{

    /**
     * Primary key for the user.
     *
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     * @var int
     */
    private $id;

    /**
     * First name of the user.
     *
     * @Column(type="string")
     * @var string
     */
    private $firstName;

    /**
     * Last name of the user.
     *
     * @Column(type="string")
     * @var string
     */
    private $lastName;

    /**
     * Username that is used for login.
     * Primary key for the user.
     *
     * @Column(type="string", nullable=true)
     * @var string
     */
    private $username;

    /**
     * Email address for the users.
     * This address is used for login and for sending notifications.
     *
     * @Column(type="string")
     * @var string
     */
    private $email;

    /**
     * Password hash that is used for login. Includes a salt.
     *
     * @Column(type="string")
     * @var string
     */
    private $password;

    /**
     * Date at which the user registered.
     *
     * @Column(type="datetime", columnDefinition="TIMESTAMP DEFAULT CURRENT_TIMESTAMP")
     * @var \DateTime
     */
    private $registered;

    /**
     * Team that the user belongs to.
     *
     * @ManyToOne(targetEntity="\Model\Team", inversedBy="members")
     * @var \Model\Team
     */
    private $team;

    /**
     * Role of the user.
     * The role concerns the special permissions for the user.
     *
     * @Column(type="string")
     * @var \Model\Enum\UserRole
     */
    private $role;

    /**
     * Activities that the user owns.
     *
     * @OneToMany(targetEntity="\Model\Activity\Activity", mappedBy="creator")
     * @var null|\Model\Activity\Activity[]
     */
    private $activities;

    /**
     * Attachments that the user owns.
     *
     * @OneToMany(targetEntity="\Model\Activity\Attachment", mappedBy="creator")
     * @var null|\Model\Activity\Attachment[]
     */
    private $attachments;

    /**
     * Password reset code.
     *
     * @Column(type="string")
     */
    private $passwordResetCode;

    /**
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getFirstName() {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     */
    public function setFirstName($firstName) {
        $this->firstName = $firstName;
    }

    /**
     * @return string
     */
    public function getLastName() {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     */
    public function setLastName($lastName) {
        $this->lastName = $lastName;
    }

    /**
     * @return string
     */
    public function getUsername() {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername($username) {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getEmail() {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email) {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getPassword() {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword($password) {
        $this->password = $password;
    }

    /**
     * @return \Model\Organization
     */
    public function getOrganization() {
        return $this->getTeam()->getOrganization();
    }

    /**
     * @return \Model\Team
     */
    public function getTeam() {
        return $this->team;
    }

    /**
     * @param \Model\Team $team
     */
    public function setTeam(Team $team) {
        $this->team = $team;
    }

    /**
     * @return \Model\Enum\UserRole
     */
    public function getRole() {
        return new Enum\UserRole($this->role);
    }

    /**
     * @param \Model\Enum\UserRole $role
     */
    public function setRole(Enum\UserRole $role) {
        $this->role = $role->value();
    }

    /**
     * @return null|\Model\Activity\Activity[]
     */
    public function getActivities() {
        return $this->activities;
    }

    /**
     * @return null|\Model\Activity\Attachment[]
     */
    public function getAttachments() {
        return $this->attachments;
    }

    /**
     * @return \DateTime
     */
    public function getRegistered() {
        return $this->registered;
    }

    /**
     * @return mixed
     */
    public function getPasswordResetCode() {
        return $this->passwordResetCode;
    }

    /**
     * @param mixed $passwordResetCode
     */
    public function setPasswordResetCode($passwordResetCode) {
        $this->passwordResetCode = $passwordResetCode;
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
            'firstName' => $this->firstName,
            'lastName' => $this->lastName,
            'username' => $this->username,
            'email' => $this->email,
            'registered' => $this->registered,
        ];
    }
}