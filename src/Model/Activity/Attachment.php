<?php
// /src/Model/Attachment.php

namespace Model\Activity;

use Doctrine\Common\Collections\ArrayCollection as ArrayCollection;

/**
 * Model for Attachments.
 *
 * @Entity
 * @Table(name="attachments")
 * @author Geert Derks <geertderks12@gmail.com>
 */
class Attachment implements \JsonSerializable
{

    /**
     * Primary key for the attachment.
     *
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     * @var int
     */
    private $id;

    /**
     * User that owns the attachment.
     *
     * @ManyToOne(targetEntity="\Model\User", inversedBy="attachments")
     * @var \Model\User
     */
    private $creator;

    /**
     * Activity that uses this attachment.
     *
     * @ManyToOne(targetEntity="\Model\Activity\Activity", inversedBy="attachments")
     * @var \Model\Activity\Activity
     */
    private $activity;
    
    /**
     * Filename of the attachment (includes extensions).
     *
     * @Column(type="string")
     * @var string
     */
    private $name;
    
    /**
     * Relative location of the file of the attachment.
     *
     * @Column(type="string")
     * @var string
     */
    private $location;

    /**
     * @return int
     */
    public function getId() {
        return $this->id;
    }
    
    /**
     * @return \Model\User
     */
    public function getCreator() {
        return $this->creator;
    }

    /**
     * @param \Model\User $creator
     */
    public function setCreator($creator) {
        $this->creator = $creator;
    }

    /**
     * @return \Model\Activity\Activity
     */
    public function getActivity() {
        return $this->activity;
    }

    /**
     * @param \Model\Activity\Activity $activity
     */
    public function setActivity(\Model\Activity\Activity $activity) {
        $this->activity = $activity;
    }

    /**
     * @return string
     */
    public function getURL() {
        return RootURL() . "/upload/" . str_replace(" ", "%20", $this->getName()) . "?" . explode(".", $this->getLocation())[0];
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
     * @return string
     */
    public function getLocation() {
        return $this->location;
    }
    
    /**
     * @param string $location
     */
    public function setLocation($location) {
        $this->location = $location;
    }
    
    /**
     * @return string
     */
    public function getPath() {
        return ABSPATH . DIRECTORY_SEPARATOR . 'upload' . DIRECTORY_SEPARATOR . $this->getLocation();
    }
    
    /**
     * @return string
     */
    public function getExtension() {
        return end(explode('.', $this->getName()));
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
            'location' => $this->location,
        ];
    }}