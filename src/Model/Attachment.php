<?php 
// /src/Model/Attachment.php

namespace Model;

use Doctrine\Common\Collections\ArrayCollection as ArrayCollection;

/**
 * @Entity
 * @Table(name="attachments")
 */
class Attachment {
	
    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     */
    private $id;
    
    private $creator;
    
    /**
     * @Column(type="string")
     */
	private $name;
    
    /**
     * @Column(type="string")
     */
    private $location;
	
	public function __construct() {
		
	}
    
    public function getId() {
        return $this->id;
    }
    
    public function getCreator() {
        return $this->creator;
    }
    
    public function getName() {
        return $this->name;
    }
    
    public function getLocation() {
        return $this->location;   
    }
    
    public function getURL() {
        return RootURL() . "/upload/" . str_replace(" ", "%20", $this->getName()) . "?" . explode(".", $this->getLocation())[0];
    }
    
    public function getPath() {
        return ABSPATH . DIRECTORY_SEPARATOR . "upload" . DIRECTORY_SEPARATOR . $this->getLocation();
    }
    
    public function getExtension() {
        return end(explode(".", $this->getName()));
    }
	
}