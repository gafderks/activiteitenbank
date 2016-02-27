<?php

namespace Service;
use Facebook\Facebook;

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
    protected $userIdSessionIndex = 'userId';

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
        if ($this->verifyLogin($user, $password) === true) {
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
     * Registers a new user with the specified attributes.
     * Assigns a random password to the user.
     *
     * @param string $email
     * @param string $firstName
     * @param string $lastName
     * @param \Model\Enum\UserRole $userRole
     * @return \Model\User|null newly created user
     * @throws \Exception if a user with the email already exists.
     */
    public function registerUser($email, $firstName, $lastName, $userRole) {
        $user = $this->getUserService()->findUserByEmail($email);
        if ($user !== null) {
            throw new \Exception("User with this email address already exists.");
        }

        // assign values to new user
        $user = new \Model\User();
        $user->setEmail($email);
        $user->setFirstName($firstName);
        $user->setLastName($lastName);
        $user->setRole($userRole);
        $user->setPassword(password_hash(mt_rand().uniqid(null, true), PASSWORD_DEFAULT)); // set random password

        // store user
        $this->getUserMapper()->persist($user);
        $this->getUserMapper()->flush();

        return $user;
    }

    /**
     * Get the user mapper.
     *
     * @return \Mapper\User
     */
    protected function getUserMapper() {
        return $this->app->mapper_user;
    }

    /**
     * Get the User service.
     *
     * @return \Service\UserService
     */
    protected function getUserService() {
        return $this->app->service_user;
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

    /**
     * Destroys the current session.
     */
    public function logoutUser() {
        session_destroy();
    }

}