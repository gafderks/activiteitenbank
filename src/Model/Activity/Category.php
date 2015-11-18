<?php
// /src/Model/Activity/Category.php

namespace Model\Activity;

/**
 * @Entity
 * @Table(name="categories")
 */
class Category {

    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     */
    private $id;

    /**
     * @Column(type="string")
     */
    private $name;

    /**
     * @ManyToOne(targetEntity="\Model\Organization", inversedBy="categories")
     **/
    private $organization;

}