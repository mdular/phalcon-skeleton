<?php

namespace Admin\Controller;

use Phalcon\Mvc\Controller;

class ArticleController extends Controller
{
    public function initialize()
    {
        $this->view->setRenderLevel(\Phalcon\Mvc\View::LEVEL_ACTION_VIEW);
        $this->response->setContentType('application/json');
    }

    public function listAction()
    {
        $page = (int) $this->dispatcher->getParam('page', 'int') ?: 1;

        // build a query
        $builder = $this->modelsManager->createBuilder()
                ->columns(['id', 'url', 'title', 'content_type', 'tags', 'state', 'author_id', 'created_at'])
                ->from('Model\Article')
                ->orderBy('created_at DESC');

        // create paginator for query
        $paginator = \Phalcon\Paginator\Factory::load([
            'builder' => $builder,
            'limit'   => 10,
            'page'    => $page,
            'adapter' => 'queryBuilder',
        ]);

        // execute paginator
        $articles = $paginator->getPaginate();

        // 404 empty pages
        if ($page > $articles->total_pages) {
            throw new \Phalcon\Mvc\Dispatcher\Exception('Resource unavailable', \Phalcon\Dispatcher::EXCEPTION_ACTION_NOT_FOUND);
        }

        $this->response->setHeader('X-Count', $articles->total_items);
        $this->response->setHeader('X-Pages', $articles->total_pages);

        return $this->response->setJsonContent($articles->items);
    }

    public function getAction()
    {
        // find one article by id
        $article = \Model\Article::findFirst([
            'conditions' => 'id = ?1',
            'bind' => [
                1 => $this->dispatcher->getParam('id', 'int'),
            ],
        ]);

        // if no article was found, return 404 Not found
        if (!$article) {
            throw new \Phalcon\Mvc\Dispatcher\Exception('Resource unavailable', \Phalcon\Dispatcher::EXCEPTION_ACTION_NOT_FOUND);
        }

        return $this->response->setJsonContent($article);
    }

    public function postAction()
    {

    }

    public function putAction()
    {
        // find one article by id
        $article = \Model\Article::findFirst([
            'conditions' => 'id = ?1',
            'bind' => [
                1 => $this->dispatcher->getParam('id', 'int'),
            ],
        ]);

        // if no article was found, return 404 Not found
        if (!$article) {
            throw new \Phalcon\Mvc\Dispatcher\Exception('Resource unavailable', \Phalcon\Dispatcher::EXCEPTION_ACTION_NOT_FOUND);
        }

        // TODO: bind, validate, save changes

        return $this->response->setJsonContent($article);
    }

    public function deleteAction()
    {

    }
}
