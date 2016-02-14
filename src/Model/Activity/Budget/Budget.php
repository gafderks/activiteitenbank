<?php
// /src/Model/Activity/Budget/Budget.php

namespace Model\Activity\Budget;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Model for Budget.
 *
 * @Entity
 * @Table(name="budgets")
 * @author Geert Derks <geertderks12@gmail.com>
 */
class Budget implements \JsonSerializable
{

    /**
     * Primary key for the budget.
     *
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     * @var int
     */
    private $id;

    /**
     * Expenses that belong to this budget.
     *
     * @OneToMany(targetEntity="\Model\Activity\Budget\Expense", mappedBy="budget")
     * @OrderBy({"position" = "ASC"})
     * @var null|\Model\Activity\Budget\Expense[]
     */
    private $expenses;

    /**
     * Budget constructor.
     */
    public function __construct() {
        $this->expenses = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @return null|\Model\Activity\Budget\Expense[]
     */
    public function getExpenses() {
        return $this->expenses;
    }

    /**
     * Returns the total cost of the activity.
     *
     * @return float|int
     */
    public function getTotalCost() {
        $total = 0;
        foreach($this->expenses as $expense) {
            $total += ($expense->getCost() * $expense->getAmount());
        }
        return $total;
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
            'expenses' => $this->expenses->toArray(),
        ];
    }}