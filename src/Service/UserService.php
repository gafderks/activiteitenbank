<?php

namespace Service;

/**
 * Class UserService
 *
 * @package Service
 */
class UserService extends Service
{

    /**
     * Returns a user based on its username.
     *
     * @param $username string
     * @return null|\Model\User
     */
    public function findUserByUsername($username) {
        return $this->getUserMapper()->findUserByUsername($username);
    }

    /**
     * Returns a user based on its email.
     *
     * @param $email string
     * @return null|\Model\User
     */
    public function findUserByEmail($email) {
        return $this->getUserMapper()->findUserByEmail($email);
    }

    /**
     * Returns a user based on its id.
     *
     * @param $id int
     * @return null|\Model\User
     */
    public function findUser($id) {
        return $this->getUserMapper()->findUserById($id);
    }

    /**
     * Get the user mapper.
     *
     * @return \Mapper\User
     */
    protected function getUserMapper() {
        return $this->container->mapper_user;
    }

}