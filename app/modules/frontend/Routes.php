<?php

namespace Frontend;

use Phalcon\Mvc\Router\Group;

class Routes extends Group
{
    const INDEX_INDEX = 'frontend-index-home';

    protected $hostname;

    public function __construct($hostname = null)
    {
        $this->hostname = $hostname;
        parent::__construct();
    }

    public function initialize()
    {
        $this->setPaths([
            'module' => 'frontend',
            'namespace' => 'Frontend\Controller',
        ]);

        $this->setHostName($this->hostname);

        // add routes below

        // match the entry page '/'
        $this->add('/', 'Index::index')->setName(self::INDEX_INDEX);
    }
}
