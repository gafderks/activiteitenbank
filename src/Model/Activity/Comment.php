<?php
// /src/Model/Activity/Comment.php

namespace Model\Activity;

use Doctrine\Common\Collections\ArrayCollection as ArrayCollection;

/**
 * Model for Comment.
 *
 * @Entity
 * @Table(name="comments")
 * @author Geert Derks <geertderks12@gmail.com>
 */
class Comment implements \JsonSerializable
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
     * Comment.
     *
     * @Column(type="text")
     * @var string
     */
    private $comment;

    /**
     * Activity that the comment is for.
     *
     * @ManyToOne(targetEntity="\Model\Activity\Activity", inversedBy="comments")
     * @var \Model\Activity\Activity
     */
    private $activity;

    /**
     * User that did the comment.
     *
     * @ManyToOne(targetEntity="\Model\User", inversedBy="ratings")
     * @JoinColumn(nullable=false)
     * @var \Model\User
     * @SWG\Property()
     */
    private $commenter;

    /**
     * Whether the commenter tried the associated activity.
     *
     * @Column(type="boolean")
     * @var boolean
     */
    private $didIt;

    /**
     * Date of the comment.
     *
     * @Column(type="datetime", columnDefinition="TIMESTAMP DEFAULT CURRENT_TIMESTAMP")
     * @var \DateTime
     */
    private $date;

    /**
     * @return \DateTime
     */
    public function getDate() {
        return $this->date;
    }

    /**
     * @param \DateTime $date
     */
    public function setDate($date) {
        $this->date = $date;
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
    public function getComment() {
        return $this->comment;
    }

    /**
     * @param string $comment
     */
    public function setComment($comment) {
        $this->comment = $comment;
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
    public function getCommenter() {
        return $this->commenter;
    }

    /**
     * @param \Model\User $commenter
     */
    public function setCommenter(\Model\User $commenter) {
        $this->commenter = $commenter;
    }

    /**
     * @return boolean
     */
    public function isDidIt() {
        return $this->didIt;
    }

    /**
     * @param boolean $didIt
     */
    public function setDidIt($didIt) {
        $this->didIt = $didIt;
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
            'comment' => $this->comment,
            'commenter' => $this->commenter->getId(),
            'activity' => $this->activity->getId(),
            'didIt' => $this->didIt,
            'date' => $this->date,
        ];
    }
}