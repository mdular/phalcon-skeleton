<?php

namespace Admin;

use Phalcon\Acl;
use Phalcon\Acl\Role;
use Phalcon\Acl\Resource;
use Phalcon\Acl\Adapter\Memory as Adapter;

class Ruleset
{
    const ROLE_GUEST = 'guest'; // not logged in
    const ROLE_ADMIN = 'admin'; // logged in as an admin user

    protected $acl;

    public function __construct()
    {
        $this->acl = new Adapter();
        $this->acl->setDefaultAction(Acl::DENY);

        // add roles to acl
        $roles = $this->getRoles();
        foreach ($roles as $role) {
            $this->acl->addRole(new Role($role));
        }

        // add private resources and actions only for the admin user role
        foreach ($this->getPrivateResources() as $resourceName => $actions) {
            $this->acl->addResource(new Resource($resourceName), $actions);

            foreach ($actions as $action) {
                $this->acl->allow(self::ROLE_ADMIN, $resourceName, $action);
            }
        }

        // add public resources and actions for all roles
        foreach ($this->getPublicResources() as $resourceName => $actions) {
            $this->acl->addResource(new Resource($resourceName), $actions);

            foreach ($roles as $role) {
                foreach ($actions as $action) {
                    $this->acl->allow($role, $resourceName, $action);
                }
            }
        }
    }

    public function isAllowed(string $role = null, string $controller = null, string $action = null):bool
    {
        return $this->acl->isAllowed($role, $controller, $action);
    }

    public function getDefaultRole():string
    {
        return self::ROLE_GUEST;
    }

    protected function getRoles():array
    {
        return [
            self::ROLE_GUEST,
            self::ROLE_ADMIN,
        ];
    }

    protected function getPrivateResources():array
    {
        return [
            'index' => ['index'],
        ];
    }

    protected function getPublicResources():array
    {
        return [
            'index' => ['login', 'logout', 'error500', 'error404', 'error403'],
        ];
    }
}
