<?php

namespace Frontend;

use Phalcon\Acl;
use Phalcon\Acl\Adapter\Memory as Adapter;

class Ruleset
{
    const ROLE_GUEST = 'guest'; // not logged in

    protected $acl;

    public function __construct()
    {
        $this->acl = new Adapter();
        $this->acl->setDefaultAction(Acl::ALLOW);
    }

    public function getDefaultRole():string
    {
        return self::ROLE_GUEST;
    }

    public function isAllowed(string $role = null, string $controller = null, string $action = null):bool
    {
        return $this->acl->isAllowed($role, $controller, $action);
    }
}
