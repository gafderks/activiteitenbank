<?php

namespace Service;
use Facebook\Facebook;
use Respect\Validation\Validator as v;

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
            if ($this->getLoggedInUser() !== null) {
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
        return $this->container['mapper_user'];
    }

    /**
     * Get the User service.
     *
     * @return \Service\UserService
     */
    protected function getUserService() {
        return $this->container['service_user'];
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
     * Returns the role of the user that is currently logged in.
     * This is primarily used for ACL.
     *
     * @return \Model\Enum\UserRole role of the logged in user
     */
    public function getLoggedInUserRole() {
        $user = $this->getLoggedInUser();
        if ($user === null) {
            return new \Model\Enum\UserRole(\Model\Enum\UserRole::Guest);
        } else {
            return $user->getRole();
        }
    }

    public function sendPasswordResetEmail($email, $ip) {
        // validate email address
        $validator = v::email();
        if (!v::email()->validate($email)) {
            throw new \Exception(_("Invalid email address"));
        }

        // check if an account is attached to the email address
        $user = $this->getUserMapper()->findUserByEmail($email);
        if (is_null($user)) {
            // send email stating that no account is connected
            try {
                $emailMessage = sprintf(
                    _("<p>You (or someone else) entered this email address when trying to change "
                        . "the password of a account for %s.</p><p>However, this email address is "
                        . "not in our database of registered users and therefore the attempted "
                        . "password change has failed.</p><p>If you have an account and were "
                        . "expecting this email, please try again using the email address you "
                        . "gave when opening your account. This may be the email address that is "
                        . "linked to your Facebook-account.</p><p>If you do not have an account "
                        . "for %s, please ignore this email.</p><p>If you keep receiving these "
                        . "emails, please contact <a href=\"mailto:%s\">%s</a>.</p>"
                        . "<p><small>This action was requested from IP address %s. Find out more "
                        . "information about this address "
                        . "<a href='http://www.ip-tracker.org/locator/ip-lookup.php?ip=%s'>here</a>."
                        . "</small></p>"),
                    $this->container['config']['applicationName'],
                    $this->container['config']['applicationName'],
                    $this->container['config']['webmasterEmail'],
                    $this->container['config']['webmasterEmail'],
                    $ip, $ip);
                $this->getMailService()->emailHtml(
                    $email,
                    sprintf("%s: %s",
                        $this->container['config']['applicationName'],
                        _("Password reset instructions")),
                    $emailMessage
                );
            } catch (\Exception $e) {
                throw new \Exception(_("Unable to send email"));
            }
        } else {
            // delete all pre-existing tokens for this user
            foreach ($this->getPasswordResetTokenMapper()->findTokensByUser($user) as $t) {
                $this->getPasswordResetTokenMapper()->remove($t);
            }
            $this->getPasswordResetTokenMapper()->flush();

            // generate new token
            $token = new \Model\PasswordResetToken($user);
            $this->getPasswordResetTokenMapper()->persist($token);
            $this->getPasswordResetTokenMapper()->flush();

            // send email with instructions
            try {
                $resetUrl = $this->container['config']['domain']
                    . $this->container['router']->pathFor('reset-password-form', ['token' => $token->getToken()]);
                $emailMessage = sprintf(
                        _("<p>We have sent this message because you requested that your password "
                        . "for %s be reset.<br>If you did not expect this message, please ignore it. "
                        . "If you keep receiving this message, please contact <a href='mailto:%s'>%s</a>.</p>"
                        . "<p>Reset your password now:<br/><a href='%s'>%s</a></p>"
                        . "<p><small>This action was requested from IP address %s. Find out more "
                        . "information about this address "
                        . "<a href='http://www.ip-tracker.org/locator/ip-lookup.php?ip=%s'>here</a>."
                        . "</small></p>"),
                    $this->container['config']['applicationName'],
                    $this->container['config']['webmasterEmail'],
                    $this->container['config']['webmasterEmail'],
                    $resetUrl, $resetUrl,
                    $ip, $ip);
                $this->getMailService()->emailHtml(
                    $email,
                    sprintf(
                        "%s: %s",
                        $this->container['config']['applicationName'],
                        _("Password reset instructions")
                    ),
                    $emailMessage);
            } catch (\Exception $e) {
                throw new \Exception(_("Unable to send email"));
            }
        }

    }

    /**
     * Destroys the current session.
     */
    public function logoutUser() {
        unset($_SESSION);
        session_destroy();
    }

    /**
     * Get the Mail service.
     *
     * @return \Service\MailService
     */
    protected function getMailService() {
        return $this->container['service_mail'];
    }

    /**
     * Get the PasswordResetToken mapper.
     *
     * @return \Mapper\PasswordResetToken
     */
    protected function getPasswordResetTokenMapper() {
        return $this->container['mapper_password_reset_token'];
    }


}