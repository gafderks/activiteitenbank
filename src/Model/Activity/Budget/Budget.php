<?php
// /src/Model/Activity/Budget/Budget.php

namespace Model\Activity\Budget;

/**
 * @Entity
 * @Table(name="budgets")
 */
class Budget {

    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     */
    private $id;

    /**
     * @OneToMany(targetEntity="\Model\Activity\Budget\Expense", mappedBy="budget")
     * @OrderBy({"order" = "ASC"})
     */
    private $expenses;

}