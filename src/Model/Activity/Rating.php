<?php
// /src/Model/Activity/Rating.php

namespace Model\Activity;

use Doctrine\Common\Collections\ArrayCollection as ArrayCollection;

/**
 * Model for Rating.
 *
 * @Entity
 * @Table(name="ratings")
 * @author Geert Derks <geertderks12@gmail.com>
 */
class Rating implements \JsonSerializable
{

    /**
     * Primary key for the rating.
     *
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     * @var int
     */
    private $id;

    /**
     * Rate.
     *
     * @Column(type="integer")
     * @var int
     */
    private $rate;

    /**
     * Activity that the rating is for.
     *
     * @ManyToOne(targetEntity="\Model\Activity\Activity", inversedBy="ratings")
     * @var \Model\Activity\Activity
     */
    private $activity;

    /**
     * User that did the rating.
     *
     * @ManyToOne(targetEntity="\Model\User", inversedBy="ratings")
     * @JoinColumn(nullable=false)
     * @var \Model\User
     * @SWG\Property()
     */
    private $rater;

    /**
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getRate() {
        return $this->rate;
    }

    /**
     * @param int $rate
     */
    public function setRate($rate) {
        $this->rate = $rate;
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
     * @return \Model\User
     */
    public function getRater() {
        return $this->rater;
    }

    /**
     * @param \Model\User $rater
     */
    public function setRater(\Model\User $rater) {
        $this->rater = $rater;
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
            'rate' => $this->rate,
            'rater' => $this->rater->getId(),
        ];
    }
}