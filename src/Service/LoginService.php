<?php


namespace Service;


class LoginService extends Service
{

    /**
     * Get the user mapper.
     *
     * @return \Mapper\User
     */
    protected function getUserMapper()
    {
        return $this->app->mapper_user;
    }

    /**
     * Tries to log in a user. Returns whether login was successful.
     *
     * @param \Model\User $user
     * @param             $password
     * @return bool
     */
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

    /**
     * Destroys the current session.
     */
    public function logout() {
        session_destroy();
    }

    /**
     * Returns the user that is currently logged in.
     *
     * @return null|\Model\User
     */
    public function getLoggedInUser() {
        return $this->getUserMapper()->findUserById($_SESSION['id']);
    }

}