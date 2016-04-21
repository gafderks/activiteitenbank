<?php
// /src/Model/PasswordResetToken.php

namespace Model;

/**
 * Model for PasswordResetToken.
 *
 * @Entity
 * @Table(name="password_reset_tokens")
 * @author Geert Derks <geertderks12@gmail.com>
 */
class PasswordResetToken
{
    /**
     * Primary key for the token.
     * This string must have high entropy, be unique, and not have a reference to the user.
     *
     * @Id
     * @Column(type="string")
     * @var string
     */
    private $token;

    /**
     * Date at which the token was generated.
     *
     * @Column(type="datetime", columnDefinition="TIMESTAMP DEFAULT CURRENT_TIMESTAMP")
     * @var \DateTime
     */
    private $generated;

    /**
     * Account that the token is for.
     *
     * @Id
     * @OneToOne(targetEntity="\Model\User")
     * @var \Model\User
     */
    private $user;

    /**
     * PasswordResetToken constructor.
     * Sets the fields of the token.
     *
     * @param User $user user for the token
     */
    public function __construct(\Model\User $user) {
        $this->user = $user;
        $this->generated = new \DateTime('now');
        $this->token = substr(
            hash('sha256',
                $user->getId()
                . $user->getEmail()
                . $this->generated->getTimestamp()
                . openssl_random_pseudo_bytes(20)
            ),
            0, 30
        );
    }

    /**
     * @return int
     */
    public function getToken() {
        return $this->token;
    }

    /**
     * @return \DateTime
     */
    public function getGenerated() {
        return $this->generated;
    }

    /**
     * @return User
     */
    public function getUser() {
        return $this->user;
    }


}