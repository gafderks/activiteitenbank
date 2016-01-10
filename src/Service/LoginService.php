<?php


namespace Service;


class LoginService extends Service
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
     * @param $email
     * @return null|\Model\User
     */
    public function findUserByEmail($email) {
        return $this->getUserMapper()->findUserByEmail($email);
    }

    /**
     * Get the user mapper.
     *
     * @return \Mapper\User
     */
    public function getUserMapper()
    {
        return $this->$app->mapper_user;
    }
}