<?php
// /src/Model/Activity/Budget/Expense.php

namespace Model\Activity\Budget;

/**
 * @Entity
 * @Table(name="budget_expenses")
 */
class Expense {

    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     */
    private $id;

    /**
     * @Column(type="integer")
     */
    private $order;

    /**
     * @ManyToOne(targetEntity="\Model\Activity\Budget\Budget", inversedBy="expenses")
     */
    private $planning;

    /**
     * @Column(type="float")
     */
    private $amount;

    /**
     * @Column(type="float")
     */
    private $cost;

    /**
     * @Column(type="string")
     */
    private $description;

}