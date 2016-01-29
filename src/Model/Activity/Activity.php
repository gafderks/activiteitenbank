<?php
// /src/Model/Activity/Activity.php

namespace Model\Activity;

use Doctrine\Common\Collections\ArrayCollection as ArrayCollection;

/**
 * Model for Activity.
 *
 * @Entity
 * @Table(name="activities")
 * @author Geert Derks <geertderks12@gmail.com>
 */
class Activity
{

    /**
     * Primary key for the activity.
     *
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     * @var int
     */
    private $id;
    
    /**
     * URL friendly name for the activity that can be used as a suffix in the URL.
     *
     * @Column(type="string")
     * @var string
     */
    private $slug;

    /**
     * Natural name of the activity.
     *
     * @Column(type="string")
     * @var string
     */
    private $name;

    /**
     * Creator of the activity.
     *
     * @ManyToOne(targetEntity="\Model\User", inversedBy="activities")
     * @var \Model\User
     */
    private $creator;
    
    /**
     * Date at which the activity was created.
     *
     * @Column(type="datetime", columnDefinition="TIMESTAMP DEFAULT CURRENT_TIMESTAMP")
     * @var \DateTime
     */
    private $created;
    
    /**
     * Date of last modification of the activity.
     *
     * @Column(type="datetime", columnDefinition="TIMESTAMP DEFAULT CURRENT_TIMESTAMP")
     * @var \DateTime
     */
    private $modified;

    /**
     * Activity areas that the activity belongs to.
     *
     * @Column(type="array")
     * @var \Model\Enum\ActivityArea[]
     */
    private $activity_areas;

    /**
     * GroupTypes that this activity is suitable for.
     *
     * @Column(type="array")
     * @var \Model\Enum\GroupType[]
     */
    private $suitable_groups;

    /**
     * The category that this activity belongs to.
     *
     * @ManyToMany(targetEntity="\Model\Activity\Category")
     * @JoinTable(name="activities_categories",
     *      joinColumns={@JoinColumn(name="activity_id", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="category_id", referencedColumnName="id", unique=true)}
     *      )
     * @var \Model\Activity\Category
     */
    private $category;
    
    /**
     * Scaled difficulty of this activity.
     *
     * @Column(type="integer")
     * @var \Model\Enum\Level
     */
    private $difficulty;
    
    /**
     * Scaled guidance needed for this activity.
     *
     * @Column(type="integer")
     * @var \Model\Enum\Level
     */
    private $guidance;

    /**
     * Scaled motivation needed for this activity.
     *
     * @Column(type="integer")
     * @var \Model\Enum\Level
     */
    private $motivation;
    
    /**
     * Elaboration for this activity.
     *
     * @Column(type="text")
     * @var string
     */
    private $elaboration;

    /**
     * Planning for this activity.
     *
     * @OneToOne(targetEntity="\Model\Activity\Planning\Planning")
     * @var \Model\Activity\Planning\Planning
     */
    private $planning;

    /**
     * Checklist for this activity.
     *
     * @OneToOne(targetEntity="\Model\Activity\Checklist\Checklist")
     * @var \Model\Activity\Checklist\Checklist
     */
    private $checklist;

    /**
     * Material list for this activity.
     *
     * @OneToOne(targetEntity="\Model\Activity\MaterialList\MaterialList")
     * @var \Model\Activity\MaterialList\MaterialList
     */
    private $materials;

    /**
     * Budget for this activity.
     *
     * @OneToOne(targetEntity="\Model\Activity\Budget\Budget")
     * @var \Model\Activity\Budget\Budget
     */
    private $budget;

    /**
     * Attachments belonging to this activity.
     *
     * @OneToMany(targetEntity="\Model\Activity\Attachment", mappedBy="activity")
     * @var null|\Model\Activity\Attachment[]
     */
    private $attachments;

    /**
     * @return int
     */
    public function getId() {
        return $this->id;
    }
    
    /**
     * @return string
     */
    public function getSlug() {
        return $this->slug;
    }
    
    /**
     * @param string $slug
     */
    public function setSlug($slug) {
        $this->slug = $slug;
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
     * @return \Model\User
     */
    public function getCreator() {
        return $this->creator;
    }
    
    /**
     * @param \Model\User $creator
     */
    public function setCreator(\Model\User $creator) {
        $this->creator = $creator;
    }

    /**
     * @return Category
     */
    public function getCategory() {
        return $this->category;
    }

    /**
     * @param \Model\Activity\Category $category
     */
    public function setCategory(Category $category) {
        $this->category = $category;
    }

    /**
     * @return \Model\Enum\Level
     */
    public function getDifficulty() {
        return new \Model\Enum\Level($this->difficulty);
    }

    /**
     * @param \Model\Enum\Level $difficulty
     */
    public function setDifficulty(\Model\Enum\Level $difficulty) {
        $this->difficulty = $difficulty->value();
    }

    /**
     * @return \Model\Enum\Level
     */
    public function getGuidance() {
        return new \Model\Enum\Level($this->guidance);
    }

    /**
     * @param \Model\Enum\Level $guidance
     */
    public function setGuidance(\Model\Enum\Level $guidance) {
        $this->guidance = $guidance->value();
    }

    /**
     * @return \Model\Enum\Level
     */
    public function getMotivation() {
        return new \Model\Enum\Level($this->motivation);
    }

    /**
     * @param \Model\Enum\Level $motivation
     */
    public function setMotivation(\Model\Enum\Level $motivation) {
        $this->motivation = $motivation->value();
    }

    /**
     * @return \Model\Activity\Budget\Budget
     */
    public function getBudget() {
        return $this->budget;
    }

    /**
     * @param \Model\Activity\Budget\Budget $budget
     */
    public function setBudget(Budget\Budget $budget) {
        $this->budget = $budget;
    }
    
    /**
     * @return \Model\Enum\ActivityArea[]
     */
    public function getActivity_areas() {
        return $this->activity_areas;
    }

    /**
     * @param \Model\Enum\ActivityArea[] $activity_areas
     */
    public function setActivity_areas(array $activity_areas) {
        $this->activity_areas = $activity_areas;
    }
    
    /**
     * @return \Model\Enum\GroupType[]
     */
    public function getSuitable_groups() {
        return $this->suitable_groups;
    }

    /**
     * @param \Model\Enum\GroupType[] $suitable_groups
     */
    public function setSuitable_groups(array $suitable_groups) {
        $this->suitable_groups = $suitable_groups;
    }

    /**
     * @return string
     */
    public function getElaboration() {
        return $this->elaboration;
    }

    /**
     * @param string $elaboration
     */
    public function setElaboration($elaboration) {
        $this->elaboration = $elaboration;
    }

    /**
     * @return \Model\Activity\Planning\Planning
     */
    public function getPlanning() {
        return $this->planning;
    }

    /**
     * @param \Model\Activity\Planning\Planning $planning
     */
    public function setPlanning(Planning\Planning $planning) {
        $this->planning = $planning;
    }

    /**
     * @return \Model\Activity\Checklist\Checklist
     */
    public function getChecklist() {
        return $this->checklist;
    }

    /**
     * @param \Model\Activity\Checklist\Checklist $checklist
     */
    public function setChecklist(Checklist\Checklist $checklist) {
        $this->checklist = $checklist;
    }

    /**
     * @return \Model\Activity\MaterialList\MaterialList
     */
    public function getMaterials() {
        return $this->materials;
    }

    /**
     * @param \Model\Activity\MaterialList\MaterialList $materials
     */
    public function setMaterials(MaterialList\MaterialList $materials) {
        $this->materials = $materials;
    }
    
    /**
     * @return null|\Model\Activity\Attachment[]
     */
    public function getAttachments() {
        return $this->attachments;
    }

}