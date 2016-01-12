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
        return $this->app->mapper_user;
    }

    public function loginUser(\Model\User $user, $password) {

        // verify stored hash against plain-text password
        if (password_verify($password, $user->getPassword())) {

            // check if a newer hashing algorithm is available or the cost has changed
            if (password_needs_rehash($user->getPassword(), PASSWORD_DEFAULT)) {
                // if so, create a new hash, and replace the old one
                $newHash = password_hash($password, PASSWORD_DEFAULT);
                $user->setPassword($newHash);
                $this->getUserMapper()->persist($user);
            }

            // set session
            $_SESSION['id'] = $user->getId();

            return true;

        }

        return false;
    }
}