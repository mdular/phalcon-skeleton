<?php

namespace Admin;

use Phalcon\Mvc\Router\Group;

class Routes extends Group
{
    const INDEX_INDEX = 'admin-index-index';
    const INDEX_LOGIN = 'admin-index-login';

    protected $hostname;

    public function __construct($hostname = null)
    {
        $this->hostname = $hostname;
        parent::__construct();
    }

    public function initialize()
    {
        $this->setPaths([
            'module' => 'admin',
            'namespace' => 'Admin\Controller',
        ]);

        $this->setHostName($this->hostname);

        // add routes below

        // match the entry page '/'
        $this->add('/', 'Index::index')->setName(self::INDEX_INDEX);

        // match login action
        $this->add('/login', 'Index::login')->setName(self::INDEX_LOGIN);
    }
}
