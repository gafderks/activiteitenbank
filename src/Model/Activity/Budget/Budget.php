<?php
// /src/Model/Activity/Budget/Budget.php

namespace Model\Activity\Budget;

/**
 * Model for Budget.
 *
 * @Entity
 * @Table(name="budgets")
 * @author Geert Derks <geertderks12@gmail.com>
 */
class Budget
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
     * @OrderBy({"order" = "ASC"})
     * @var null|\Model\Activity\Budget\Expense[]
     */
    private $expenses;

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
}