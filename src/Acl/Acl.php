<?php

namespace Acl;

use Zend\Permissions\Acl\Acl as ZendAcl;
use \Model\Enum\UserRole as Role;

/**
 * Class Acl
 *
 * Handles the Access Control List.
 *
 * @package Acl
 */
class Acl extends ZendAcl
{

    /**
     * AclService constructor.
     */
    public function __construct() {
        // Application roles
        $this->addRole(Role::Guest);
        /* member role "extends" guest, meaning the member role will get all of
         the guest role permissions by default */
        $this->addRole(Role::Member, Role::Guest);
        $this->addRole(Role::Moderator, Role::Member);
        // the administrator is a role on its own that does not extend another role
        $this->addRole(Role::Admin);

        // Application resources
        // pages
        $this->addResource('login');
        $this->addResource('settings');
        $this->addResource('explorer');
        // api
        $this->addResource('token');
        $this->addResource('activity');
        $this->addResource('ownActivity');
        $this->addResource('comment');
        $this->addResource('rating');

        // Application permissions
        /* Now we allow or deny a role's access to resources. The third
        argument is 'privilege'. */
        $this->allow(Role::Guest, 'login');
        $this->allow(Role::Guest, 'explorer');
        $this->allow(Role::Guest, 'activity', ['view', 'download']);

        $this->allow(Role::Member, 'activity', ['create']);
        $this->allow(Role::Member, 'ownActivity'); // members have full control over their own activities
        $this->allow(Role::Member, 'rating'); // ratings can only be updated
        $this->allow(Role::Member, 'comment', ['create']);
        
        $this->allow(Role::Member, 'settings');
        $this->allow(Role::Member, 'token');

        $this->allow(Role::Moderator, 'activity', ['edit', 'delete']);
        $this->allow(Role::Moderator, 'comment', ['edit', 'delete']);

        // This allows admin access to everything
        $this->allow(Role::Admin);
    }

}