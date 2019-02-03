<?php

namespace Admin;

use Phalcon\Mvc\Router\Group;

class Routes extends Group
{
    const INDEX_INDEX = 'admin-index-index';
    const INDEX_LOGIN = 'admin-index-login';
    const INDEX_LOGOUT = 'admin-index-logout';

    const API1_ARTICLES_LIST = 'api1-articles-list';
    const API1_ARTICLE_CREATE = 'api1-article-create';
    const API1_ARTICLE_READ = 'api1-article-read';
    const API1_ARTICLE_UPDATE = 'api1-article-update';
    const API1_ARTICLE_DELETE = 'api1-article-delete';

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
        $this->addGet('/api/v1/articles{page:(?:\?page=([0-9]+))?}', 'Article::list')->setName(self::API1_ARTICLES_LIST);
        $this->addPost('/api/v1/article', 'Article::post')->setName(self::API1_ARTICLE_CREATE);
        $this->addGet('/api/v1/article/{id:([0-9]+)}', 'Article::get')->setName(self::API1_ARTICLE_READ);
        $this->addPut('/api/v1/article/{id:([0-9]+)}', 'Article::put')->setName(self::API1_ARTICLE_UPDATE);
        $this->addDelete('/api/v1/article/{id:([0-9]+)}', 'Article::delete')->setName(self::API1_ARTICLE_DELETE);
    }
}
