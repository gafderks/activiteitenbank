<?php
// /src/Model/Activity/Budget/Expense.php

namespace Model\Activity\Budget;

/**
 * Model for Budget Expense.
 *
 * @Entity
 * @Table(name="budget_expenses")
 * @author Geert Derks <geertderks12@gmail.com>
 */
class Expense
{

    /**
     * Primary key for the expense.
     *
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     * @var int
     */
    private $id;

    /**
     * Ordering constant. Order is ascending on this value.
     *
     * @Column(type="integer")
     * @var int
     */
    private $order;

    /**
     * Budget that this expense belongs to.
     *
     * @ManyToOne(targetEntity="\Model\Activity\Budget\Budget", inversedBy="expenses")
     * @var \Model\Activity\Budget\Budget
     */
    private $budget;

    /**
     * Amount of this expense.
     *
     * @Column(type="float")
     * @var float
     */
    private $amount;

    /**
     * Cost of this expense.
     *
     * @Column(type="float")
     * @var float
     */
    private $cost;

    /**
     * Description of this expense.
     *
     * @Column(type="string")
     * @var string
     */
    private $description;

    /**
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getOrder() {
        return $this->order;
    }

    /**
     * @param int $order
     */
    public function setOrder($order) {
        $this->order = $order;
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
    public function setBudget(Budget $budget) {
        $this->budget = $budget;
    }

    /**
     * @return float
     */
    public function getAmount() {
        return $this->amount;
    }

    /**
     * @param float $amount
     */
    public function setAmount($amount) {
        $this->amount = $amount;
    }

    /**
     * @return float
     */
    public function getCost() {
        return $this->cost;
    }

    /**
     * @param float $cost
     */
    public function setCost($cost) {
        $this->cost = $cost;
    }

    /**
     * @return string
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description) {
        $this->description = $description;
    }


}