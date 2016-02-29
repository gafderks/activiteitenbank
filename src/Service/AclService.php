<?php

namespace Service;

use Zend\Permissions\Acl\Acl as ZendAcl;

/**
 * Class AclService
 *
 * Handles the Access Control List.
 *
 * @package Service
 */
class AclService extends ZendAcl
{

    /**
     * AclService constructor.
     */
    public function __construct() {
        // Application roles
        $this->addRole('guest');
        /* member role "extends" guest, meaning the member role will get all of
         the guest role permissions by default */
        $this->addRole('member', 'guest');
        $this->addRole('moderator', 'member');
        $this->addRole('admin');

        // Application resources
        $this->addResource('login');
        $this->addResource('explorer');
        $this->addResource('activity');
        $this->addResource('ownActivity');



        // Application permissions
        /* Now we allow or deny a role's access to resources. The third
        argument is 'privilege'. We are using HTTP method as 'privilege'. */
        $this->allow('guest', 'login');
        $this->allow('guest', 'explorer');
        $this->allow('guest', 'activity', ['view', 'download']);

        $this->allow('member', 'activity', ['create']);
        $this->allow('member', 'ownActivity'); // members have full control over their own activities

        $this->allow('moderator', 'activity', ['edit', 'delete']);


        // This allows admin access to everything
        $this->allow('admin');
    }

}