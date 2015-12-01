<?php 
// /src/Model/Activity/Activity.php

namespace Model\Activity;

use Doctrine\Common\Collections\ArrayCollection as ArrayCollection;

/**
 * @Entity
 * @Table(name="activities")
 */
class Activity {
	    
    /**
	 * Unique identifier for the activity.
	 *
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     */
    private $id;
    
    /**
	 * URL friendly name for the activity that can be used as a suffix in the URL.
	 *
     * @Column(type="string")
     */
	private $slug;
	
    /**
	 * Name of the activity.
	 *
     * @Column(type="string")
     */
    private $name;

	/**
	 * Creator of the activity.
	 *
	 * @ManyToOne(targetEntity="\Model\User", inversedBy="activities")
	 */
    private $creator;
    
    /**
	 * Date at which the activity was created.
	 *
     * @Column(type="datetime", columnDefinition="TIMESTAMP DEFAULT CURRENT_TIMESTAMP")
     */
    private $created;
    
    /**
	 * Date of last modification of the activity.
	 *
     * @Column(type="datetime")
     */
    private $modified;

    /**
     * @ManyToOne(targetEntity="\Model\Team", inversedBy="activities")
     */
    private $team;

    /**
     * @ManyToOne(targetEntity="\Model\Organization", inversedBy="activities")
     */
    private $organization;

    private $activity_areas;
    private $suitable_groups;

    /**
     * @ManyToMany(targetEntity="\Model\Activity\Category")
     * @JoinTable(name="activities_categories",
     *      joinColumns={@JoinColumn(name="activity_id", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="category_id", referencedColumnName="id", unique=true)}
     *      )
     */
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
     * @Column(type="text")
     */
    private $elaboration;

    /**
     * @OneToOne(targetEntity="\Model\Activity\Planning\Planning")
     */
    private $planning;

    /**
     * @OneToOne(targetEntity="\Model\Activity\Checklist\Checklist")
     */
    private $checklist;

	/**
	 * @OneToOne(targetEntity="\Model\Activity\MaterialList\MaterialList")
	 */
    private $materials;

    /**
     * @OneToOne(targetEntity="\Model\Activity\Budget\Budget")
     */
    private $budget;

    /**
     * @OneToMany(targetEntity="\Model\Activity\Attachment", mappedBy="activity")
     */
    private $attachments;
	
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

	public function getChecklist(){
		return $this->checklist;
	}

	public function setChecklist($checklist){
		$this->checklist = $checklist;
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