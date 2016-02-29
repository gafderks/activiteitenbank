<?php
// /src/Model/Enum/UserRole.php

namespace Model\Enum;

/**
 * Enum UserRole
 *
 * @author Geert Derks <geertderks12@gmail.com>
 */
class UserRole extends Enum {

    const Guest = 'guest';
    const Member = 'member';
    const Moderator = 'mod';
    const Admin = 'admin';

}