<?php
// /src/Model/Activity/Activity.php

namespace Model\Activity;

use Doctrine\Common\Collections\ArrayCollection as ArrayCollection;

/**
 * Model for Activity.
 *
 * @Entity
 * @Table(name="activities")
 * @SWG\Definition()
 * @author Geert Derks <geertderks12@gmail.com>
 */
class Activity implements \JsonSerializable
{

    /**
     * Primary key for the activity.
     *
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     * @var int
     * @SWG\Property()
     */
    private $id;
    
    /**
     * URL friendly name for the activity that can be used as a suffix in the URL.
     *
     * @Column(type="string")
     * @var string
     * @SWG\Property()
     */
    private $slug;

    /**
     * Natural name of the activity.
     *
     * @Column(type="string")
     * @var string
     * @SWG\Property()
     */
    private $name;

    /**
     * Creator of the activity.
     *
     * @ManyToOne(targetEntity="\Model\User", inversedBy="activities")
     * @JoinColumn(nullable=false)
     * @var \Model\User
     * @SWG\Property()
     */
    private $creator;
    
    /**
     * Date at which the activity was created.
     *
     * @Column(type="datetime", columnDefinition="TIMESTAMP DEFAULT CURRENT_TIMESTAMP")
     * @var \DateTime
     * @SWG\Property()
     */
    private $created;
    
    /**
     * Date of last modification of the activity.
     *
     * @Column(type="datetime", columnDefinition="TIMESTAMP DEFAULT CURRENT_TIMESTAMP")
     * @var \DateTime
     * @SWG\Property()
     */
    private $modified;

    /**
     * Activity areas that the activity belongs to.
     *
     * @Column(type="simple_array", nullable=true)
     * @var \Model\Enum\ActivityArea[]
     * @SWG\Property()
     */
    private $activityAreas;

    /**
     * GroupTypes that this activity is suitable for.
     *
     * @Column(type="simple_array", nullable=true)
     * @var \Model\Enum\GroupType[]
     * @SWG\Property()
     */
    private $suitableGroups;

    /**
     * The categories that this activity belongs to.
     *
     * @ManyToMany(targetEntity="\Model\Activity\Category")
     * @JoinTable(name="activities_categories",
     *      joinColumns={@JoinColumn(name="activity_id", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="category_id", referencedColumnName="id", unique=true)}
     *      )
     * @var \Model\Activity\Category
     * @SWG\Property()
     */
    private $categories;
    
    /**
     * Scaled difficulty of this activity.
     *
     * @Column(type="integer")
     * @var \Model\Enum\Level
     * @SWG\Property()
     */
    private $difficulty;
    
    /**
     * Scaled guidance needed for this activity.
     *
     * @Column(type="integer")
     * @var \Model\Enum\Level
     * @SWG\Property()
     */
    private $guidance;

    /**
     * Scaled motivation needed for this activity.
     *
     * @Column(type="integer")
     * @var \Model\Enum\Level
     * @SWG\Property()
     */
    private $motivation;

    /**
     * Minimal number of participants.
     *
     * @Column(type="integer", nullable=true)
     * @var int
     * @SWG\Property()
     */
    private $groupSizeMin;

    /**
     * Maximal number of participants.
     *
     * @Column(type="integer", nullable=true)
     * @var int
     * @SWG\Property()
     */
    private $groupSizeMax;

    /**
     * Elaboration for this activity.
     *
     * @Column(type="text")
     * @var string
     * @SWG\Property()
     */
    private $elaboration;

    /**
     * Planning for this activity.
     *
     * @OneToOne(targetEntity="\Model\Activity\Planning\Planning")
     * @var \Model\Activity\Planning\Planning
     * @SWG\Property()
     */
    private $planning;

    /**
     * Checklist for this activity.
     *
     * @OneToOne(targetEntity="\Model\Activity\Checklist\Checklist")
     * @var \Model\Activity\Checklist\Checklist
     * @SWG\Property()
     */
    private $checklist;

    /**
     * Material list for this activity.
     *
     * @OneToOne(targetEntity="\Model\Activity\MaterialList\MaterialList")
     * @var \Model\Activity\MaterialList\MaterialList
     * @SWG\Property()
     */
    private $materials;

    /**
     * Budget for this activity.
     *
     * @OneToOne(targetEntity="\Model\Activity\Budget\Budget")
     * @var \Model\Activity\Budget\Budget
     * @SWG\Property()
     */
    private $budget;

    /**
     * Attachments belonging to this activity.
     *
     * @OneToMany(targetEntity="\Model\Activity\Attachment", mappedBy="activity")
     * @var null|\Model\Activity\Attachment[]
     * @SWG\Property()
     */
    private $attachments;

    /**
     * Activity constructor.
     */
    public function __construct() {
        $this->categories = new ArrayCollection();
        $this->attachments = new ArrayCollection();
        $this->activityAreas = new \Zend\Stdlib\ArrayObject();
        $this->suitableGroups = new \Zend\Stdlib\ArrayObject();
    }

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
     * @return \Model\Activity\Category[]
     */
    public function getCategories() {
        return $this->categories;
    }

    /**
     * @param \Model\Activity\Category[] $category
     */
    public function setCategories(array $categories) {
        $this->categories = $categories;
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
     * @return null|int
     */
    public function getGroupSizeMax() {
        return $this->groupSizeMax;
    }

    /**
     * @param null|int $groupSizeMax
     */
    public function setGroupSizeMax($groupSizeMax) {
        $this->groupSizeMax = $groupSizeMax;
    }

    /**
     * @return null|int
     */
    public function getGroupSizeMin() {
        return $this->groupSizeMin;
    }

    /**
     * @param null|int $groupSizeMin
     */
    public function setGroupSizeMin($groupSizeMin) {
        $this->groupSizeMin = $groupSizeMin;
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
     * @return \DateTime
     */
    public function getCreated() {
        return $this->created;
    }

    /**
     * @param \DateTime $created
     */
    public function setCreated(\DateTime $created) {
        $this->created = $created;
    }

    /**
     * @return \DateTime
     */
    public function getModified() {
        return $this->modified;
    }

    /**
     * @param \DateTime $modified
     */
    public function setModified(\DateTime $modified) {
        $this->modified = $modified;
    }
    
    /**
     * @return \Model\Enum\ActivityArea[]
     */
    public function getActivityAreas() {
        $r = [];
        foreach ($this->activityAreas as $activityArea) {
            array_push($r, new \Model\Enum\ActivityArea($activityArea));
        }

        return $r;
    }

    /**
     * @param \Model\Enum\ActivityArea[] $activityAreas
     */
    public function setActivityAreas(array $activityAreas) {
        $this->activityAreas = $activityAreas;
    }

    /**
     * @return int[]
     */
    public function getActivityAreasRaw() {
        return $this->activityAreas;
    }
    
    /**
     * @return \Model\Enum\GroupType[]
     */
    public function getSuitableGroups() {
        $r = [];
        foreach ($this->suitableGroups as $suitableGroup) {
            array_push($r, new \Model\Enum\GroupType($suitableGroup));
        }

        return $r;
    }

    /**
     * @param \Model\Enum\GroupType[] $suitableGroups
     */
    public function setSuitable_groups(array $suitableGroups) {
        $this->suitableGroups = $suitableGroups;
    }

    /**
     * @return string[]
     */
    public function getSuitableGroupsRaw() {
        return $this->suitableGroups;
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
        return $this->attachments->toArray();
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
            'id'             => $this->id,
            'slug'           => $this->slug,
            'name'           => $this->name,
            //'creator' => $this->creator,
            'created'        => $this->created,
            'modified'       => $this->modified,
            'activityAreas'  => $this->activityAreas,
            'suitableGroups' => $this->suitableGroups,
            'categories'     => $this->categories,
            'difficulty'     => $this->difficulty,
            'guidance'       => $this->guidance,
            'motivation'     => $this->motivation,
            'groupSizeMin'   => $this->groupSizeMin,
            'groupSizeMax'   => $this->groupSizeMax,
            'elaboration'    => $this->elaboration,
            'planning'       => $this->planning,
            'checklist'      => $this->checklist,
            'materials'      => $this->materials,
            'budget'         => $this->budget,
            'attachments'    => $this->attachments->toArray(),
        ];
    }
}