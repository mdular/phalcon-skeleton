<?php

namespace Frontend;

use Phalcon\Mvc\Router\Group;

class Routes extends Group
{
    const INDEX_INDEX = 'frontend-index-home';
    const INDEX_PAGE = 'frontend-index-page';
    const INDEX_ARTICLE = 'frontend-index-article';

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
            'namespace' => 'Frontend\Controllers',
        ]);

        $this->setHostName($this->hostname);

        // add routes below

        // match the entry page '/'
        $this->add('/', 'Index::index')->setName(self::INDEX_INDEX);

        // match article pages /page/2-infinity
        $this->add('/page/{page:([0-9]{2,}|[2-9])}', 'Index::index')->setName(self::INDEX_PAGE);

        // match an article
        $this->add('/article/{url:([a-zA-Z0-9_-]+)}', 'Index::article')->setName(self::INDEX_ARTICLE);
    }
}
