<?php 
// /src/Model/Activity.php

namespace Model;

use Doctrine\Common\Collections\ArrayCollection as ArrayCollection;

/**
 * @Entity
 * @Table(name="activities")
 */
class Activity {
	    
    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     */
    private $id;
    
    /**
     * @Column(type="string")
     */
	private $slug;
	
    /**
     * @Column(type="string")
     */
    private $name;
    
    private $creator;
    
    /**
     * @Column(type="datetime", columnDefinition="TIMESTAMP DEFAULT CURRENT_TIMESTAMP")
     */
    private $created;
    
    /**
     * @Column(type="datetime")
     */
    private $modified;
    
    private $team;
    
    private $organization;
        
    private $activity_areas;
    private $suitable_groups;
        
    private $category;
    
    /**
     * @Column(type="integer")
     */
    private $difficulty;
    
    /**
     * @Column(type="integer")
     */
	private $guidance;
	
    /**
     * @Column(type="integer")
     */
    private $motivation;
	
    /**
     * @Column(type="integer")
     */
    private $duration; // how to do this efficiently?
	
    /**
     * @Column(type="integer")
     */
    private $budget; // how to do this efficiently?
    
    /** 
     * Column(type="text")
     */
    private $elaboration;
    private $planning;
    private $preparations;
    private $materials;
    private $budgetary;
    
    private $attachments;
	
	private $db;
	
	public function __construct($db, $identifier) {
		
		
	}
    
    public function getId(){
		return $this->id;
	}
    
	public function getSlug(){
		return $this->slug;
	}
    
	public function setSlug($slug){
		$this->slug = $slug;
	}
    
	public function getName(){
		return $this->name;
	}
    
    public function setName($name){
		$this->name = $name;
	}
    
	public function getCreator(){
		return $this->creator;
	}
    
    public function setCreator(\Model\User $creator){
		$this->creator = $creator;
	}

	public function getTeam(){
		return $this->team;
	}

	public function setTeam(\Model\Team $team){
		$this->team = $team;
	}

	public function getCategory(){
		return $this->category;
	}

	public function setCategory($category){
		$this->category = $category;
	}

	public function getDifficulty(){
		return $this->difficulty;
	}

	public function setDifficulty($difficulty){
		$this->difficulty = $difficulty;
	}

	public function getGuidance(){
		return $this->guidance;
	}

	public function setGuidance($guidance){
		$this->guidance = $guidance;
	}

	public function getMotivation(){
		return $this->motivation;
	}

	public function setMotivation($motivation){
		$this->motivation = $motivation;
	}

	public function getDuration(){
		return $this->duration;
	}

	public function setDuration($duration){
		$this->duration = $duration;
	}

	public function getBudget(){
		return $this->budget;
	}

	public function setBudget($budget){
		$this->budget = $budget;
	}
    
    public function getActivity_areas(){
		return $this->activity_areas;
	}

	public function setActivity_areas($activity_areas){
		$this->activity_areas = $activity_areas;
	}
    
    public function getSuitable_groups(){
		return $this->suitable_groups;
	}

	public function setSuitable_groups($suitable_groups){
		$this->suitable_groups = $suitable_groups;
	}

	public function getElaboration(){
		return $this->elaboration;
	}

	public function setElaboration($elaboration){
		$this->elaboration = $elaboration;
	}

	public function getPlanning(){
		return $this->planning;
	}

	public function setPlanning($planning){
		$this->planning = $planning;
	}

	public function getPreparations(){
		return $this->preparations;
	}

	public function setPreparations($preparations){
		$this->preparations = $preparations;
	}

	public function getMaterials(){
		return $this->materials;
	}

	public function setMaterials($materials){
		$this->materials = $materials;
	}

	public function getBudgetary(){
		return $this->budgetary;
	}

	public function setBudgetary($budgetary){
		$this->budgetary = $budgetary;
	}
    
    public function getAttachments() {
        return $this->attachments;
    }

}