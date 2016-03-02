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
class FacebookService extends LoginService
{

    /**
     * Tries to log in a user. Returns whether login was successful.
     *
     * @return bool whether login was successful
     * @throws \Exception when some user is already logged in
     */
    public function loginUser() {
        $fb = $this->getFacebookObject();

        $helper = $fb->getRedirectLoginHelper();
        try {
            $accessToken = $helper->getAccessToken();
        } catch (\Facebook\Exceptions\FacebookResponseException $e) {
            // When Graph returns an error
            throw new \Exception('Graph returned an error: ' . $e->getMessage());
        } catch(\Facebook\Exceptions\FacebookSDKException $e) {
            // When validation fails or other local issues
            throw new \Exception('Facebook SDK returned an error: ' . $e->getMessage());
        }

        if (isset($accessToken)) {
            $_SESSION['facebook_access_token'] = (string) $accessToken;

            if ($this->getLoggedInUser() !== null) {
                throw new \Exception('A user is already logged in');
            }

            $fb->setDefaultAccessToken($accessToken);
            $response = $fb->get('/me?fields=email,first_name,last_name', $accessToken);
            $details = $response->getGraphUser();

            $email = $details->getEmail();
            if ($email === null) {
                throw new \Exception('Your Facebook account is not linked to an email address');
            }
            $firstName = $details->getFirstName();
            $lastName = $details->getLastName();

            // get User object with activity
            $user = $this->getUserService()->findUserByEmail($email);
            if ($user === null) {
                $user = $this->registerUser($email, $firstName, $lastName,
                    new \Model\Enum\UserRole(\Model\Enum\UserRole::Plain));
            }

            // store userId in session
            $_SESSION[$this->userIdSessionIndex] = $user->getId();

            // return that login has succeeded
            return true;
        } else {
            if ($helper->getError()) {
                throw new \Exception($helper->getError());
            } else {
                throw new \Exception('Facebook login failed');
            }
        }
    }

    /**
     * Returns a URL that can be used to login with Facebook.
     *
     * @return string
     */
    public function getFacebookLoginUrl() {
        $fb = $this->getFacebookObject();

        $helper = $fb->getRedirectLoginHelper();
        $permissions = ['email', 'public_profile'];
        $loginUrl = $helper->getLoginUrl($this->container->config['domain'] .
            $this->container->router->pathFor('facebook-login-callback'),
            $permissions);
        return $loginUrl;
    }

    /**
     * Returns a Facebook SDK object.
     *
     * @return Facebook
     */
    public function getFacebookObject() {
        return new \Facebook\Facebook([
            'app_id' => $this->container->config['facebook']['app_id'],
            'app_secret' => $this->container->config['facebook']['app_secret'],
            'default_graph_version' => $this->container->config['facebook']['default_graph_version'],
        ]);
    }

}