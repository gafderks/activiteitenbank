<?php
// /src/Mapper/Category.php

namespace Mapper;
use Exception\ActivityNotFoundException;
use Respect\Validation\Exceptions\NullTypeException;

/**
 * Mappers for Categories.
 *
 * @package Mapper
 */
class Category extends \Mapper\Mapper
{
    /**
     * Returns a category based on its id.
     *
     * @param $id integer id of the category to return
     * @return null|\Model\Activity\Category
     * @throws CategoryNotFoundException if the category with he specified id does not exist
     */
    public function findCategoryById($id) {
        $category =  $this->getRepository()->find($id);
        if (is_null($category)) {
            throw new \Exception\CategoryNotFoundException("Category with the ID $id was not found");
        }
        return $category;
    }

    /**
     * Returns all categories.
     *
     * @return array
     */
    public function findAll() {
        return $this->getRepository()->findAll();
    }

    /**
     * Persists an object.
     *
     * @param $object
     */
    public function persist($object) {
        $this->em->persist($object);
    }

    /**
     * Removes a category.
     *
     * @param \Model\Activity\Category $category
     */
    public function remove(\Model\Activity\Category $category) {
        $this->em->remove($category);
    }

    /**
     * Get the repository for this mapper.
     *
     * @return \Doctrine\ORM\EntityRepository
     */
    public function getRepository() {
        return $this->em->getRepository('\Model\Activity\Category');
    }

}