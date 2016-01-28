<?php


namespace Service;


/**
 * Class LoginService
 *
 * Provides methods for logging in users and for obtaining the currently logged in user.
 *
 * @package Service
 */
class LoginService extends Service
{

    /**
     * @var string Index in the session variable where the Id of the currently logged in user is stored.
     */
    private $userIdSessionIndex = 'userId';

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
     * @throws \Exception when some user is already logged in
     */
    public function loginUser(\Model\User $user, $password) {
        // check if valid credentials were given
        if (true === $this->verifyLogin($user, $password)) {
            // check if there is already a user logged in
            if (null !== $this->getLoggedInUser()) {
                throw new \Exception('A user is already logged in');
            }

            // store userId in session
            $_SESSION[$this->userIdSessionIndex] = $user->getId();

            // communicate that login succeeded
            return true;

        } else {
            return false;
        }
    }

    /**
     * Verifies whether login credentials are valid.
     *
     * @param \Model\User $user
     * @param             $password
     * @return bool
     */
    public function verifyLogin(\Model\User $user, $password) {
        // verify stored hash against plain-text password
        if (password_verify($password, $user->getPassword())) {

            // check if a newer hashing algorithm is available or the cost has changed
            if (password_needs_rehash($user->getPassword(), PASSWORD_DEFAULT)) {
                // if so, create a new hash, and replace the old one
                $newHash = password_hash($password, PASSWORD_DEFAULT);
                $user->setPassword($newHash);
                $this->getUserMapper()->persist($user);
            }

            return true;
        }

        return false;
    }

    /**
     * Destroys the current session.
     */
    public function logoutUser() {
        session_destroy();
    }

    /**
     * Returns the user that is currently logged in.
     *
     * @return null|\Model\User
     */
    public function getLoggedInUser() {
        if (!isset($_SESSION[$this->userIdSessionIndex])) {
            return null;
        } else {
            return $this->getUserMapper()->findUserById($_SESSION[$this->userIdSessionIndex]);
        }
    }

}