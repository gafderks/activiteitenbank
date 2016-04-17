<?php
// /src/Mapper/PasswordResetToken.php

namespace Mapper;

/**
 * Mappers for PasswordResetTokens.
 *
 * @package Mapper
 */
class PasswordResetToken extends \Mapper\Mapper
{

    /**
     * Returns all tokens for a user.
     *
     * @param \Model\User $user
     * @return array
     */
    public function findTokenByUser(\Model\User $user) {
        return $this->getRepository()->findBy([
            'user' => $user->getId()
        ]);
    }

    /**
     * Returns a token based on its id.
     *
     * @param $id string id of the token to return
     * @return null|\Model\PasswordResetToken
     */
    public function findTokenById($id) {
        return $this->getRepository()->find($id);
    }

    /**
     * Removes a token.
     *
     * @param $token \Model\PasswordResetToken
     */
    public function remove($token) {
        $this->em->remove($token);
    }

    /**
     * Persist a token.
     *
     * @param $token \Model\PasswordResetToken
     */
    public function persist($token) {
        $this->em->persist($token);
    }

    /**
     * Get the repository for this mapper.
     *
     * @return \Doctrine\ORM\EntityRepository
     */
    public function getRepository()
    {
        return $this->em->getRepository('\Model\PasswordResetToken');
    }
    
}