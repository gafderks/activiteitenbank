<?php
// /src/Mapper/User.php

namespace Mapper;

/**
 * Mappers for Users.
 *
 * @package Mapper
 */
class User extends \Mapper\Mapper
{

    /**
     * Returns a user based on its username.
     *
     * @param $username string
     * @return null|\Model\User
     */
    public function findUserByUsername($username) {
        return $this->getRepository()->findOneBy([
            'username' => $username
        ]);
    }

    /**
     * Returns a user based on its email.
     *
     * @param $email
     * @return null|\Model\User
     */
    public function findUserByEmail($email) {
        return $this->getRepository()->findOneBy([
            'email' => $email
        ]);
    }

    /**
     * Returns a user based on its id.
     *
     * @param $id integer id of the user to return
     * @return null|\Model\User
     */
    public function findUserById($id) {
        return $this->getRepository()->find($id);
    }

    /**
     * Removes a user.
     *
     * @param $user \Model\User
     */
    public function remove($user) {
        $this->em->remove($user);
    }

    /**
     * Persist a user.
     *
     * @param $user \Model\User
     */
    public function persist($user) {
        $this->em->persist($user);
    }

    /**
     * Get the repository for this mapper.
     *
     * @return \Doctrine\ORM\EntityRepository
     */
    public function getRepository()
    {
        return $this->em->getRepository('\Model\User');
    }
    
}