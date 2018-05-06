<?php

namespace Admin;

use Phalcon\Mvc\Router\Group;

class Routes extends Group
{
    const INDEX_INDEX = 'admin-index-index';
    const INDEX_LOGIN = 'admin-index-login';
    const INDEX_LOGOUT = 'admin-index-logout';

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

        // match logout action
        $this->add('/logout', 'Index::logout')->setName(self::INDEX_LOGOUT);

        // match article actions
        $this->addGet('/api/v1/articles{page:(?:\?page=([0-9]+))?}', 'Article::list');
        $this->addGet('/api/v1/article{id:/([0-9]+)}', 'Article::get');
        $this->addPost('/api/v1/article', 'Article::post');
        $this->addPut('/api/v1/article/{id:/([0-9]+)}', 'Article::put');
        $this->addDelete('/api/v1/article/{id:/([0-9]+)}', 'Article::delete');
    }
}
