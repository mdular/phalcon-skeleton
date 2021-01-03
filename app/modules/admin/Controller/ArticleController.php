<?php

namespace Admin\Controller;

use Admin\Routes;
use Phalcon\Mvc\Controller;

class ArticleController extends Controller
{
    const ARTICLE_RESPONSE_FIELDS = ['url', 'title', 'excerpt', 'content', 'content_type', 'tags', 'state', 'author_id', 'published_at'];

    public function initialize()
    {
        $this->view->setRenderLevel(\Phalcon\Mvc\View::LEVEL_ACTION_VIEW);
        $this->response->setContentType('application/json');
    }

    /**
     * @return \Phalcon\Http\Response|\Phalcon\Http\ResponseInterface
     * @throws \Phalcon\Mvc\Dispatcher\Exception
     */
    public function listAction()
    {
        $page = (int) $this->dispatcher->getParam('page', 'int') ?: 1;

        // build a query
        $builder = $this->modelsManager->createBuilder()
                ->columns(['id', 'url', 'title', 'content_type', 'tags', 'state', 'author_id', 'created_at'])
                ->from(\Model\Article::class)
                ->orderBy('id DESC');

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

    /**
     * @return \Phalcon\Http\Response|\Phalcon\Http\ResponseInterface
     * @throws \Phalcon\Mvc\Dispatcher\Exception
     */
    public function postAction()
    {
        $data = $this->request->getJsonRawBody(true);
        unset($data['messages']);

        if (is_null($data) || isset($data['id']) || isset($data['created_at']) || isset($data['updated_at'])) {
            throw new \Phalcon\Mvc\Dispatcher\Exception('Bad Request', \Phalcon\Dispatcher::EXCEPTION_INVALID_PARAMS);
        }

        $article = new \Model\Article($data);
        $article->setAuthorId($this->session->get('auth')['id']);

        // validate + save changes, or show errors
        if ($article->validation() && $article->save($data)) {
            $this->response->setStatusCode(201);
            return $this->response->setHeader('Location', $this->url->get([
                'for' => Routes::API1_ARTICLE_READ,
                'id' => $article->getId(),
            ]));
        }

        $response = array_merge(['messages' => []], $article->toArray(self::ARTICLE_RESPONSE_FIELDS));
        foreach ($article->getMessages() as $message) {
            $response['messages'][$message->getField()] = $message->getMessage();
        }

        $this->response->setStatusCode(400);
        return $this->response->setJsonContent($response);
    }

    /**
     * @return \Phalcon\Http\Response|\Phalcon\Http\ResponseInterface
     * @throws \Phalcon\Mvc\Dispatcher\Exception
     */
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

        return $this->response->setJsonContent($article->toArray(self::ARTICLE_RESPONSE_FIELDS));
    }

    /**
     * @return \Phalcon\Http\Response|\Phalcon\Http\ResponseInterface
     * @throws \Phalcon\Mvc\Dispatcher\Exception
     */
    public function putAction()
    {
        $data = $this->request->getJsonRawBody(true);
        unset($data['messages']);

        if (is_null($data) || isset($data['id']) || isset($data['created_at']) || isset($data['updated_at'])) {
            throw new \Phalcon\Mvc\Dispatcher\Exception('Bad Request', \Phalcon\Dispatcher::EXCEPTION_INVALID_PARAMS);
        }

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

        // validate + save changes, or show errors
        if ($article->validation() && $article->save($data)) {
            return $this->response->setJsonContent($article->toArray(self::ARTICLE_RESPONSE_FIELDS));
        }

        $response = array_merge(['messages' => []], $article->toArray(self::ARTICLE_RESPONSE_FIELDS));
        foreach ($article->getMessages() as $message) {
            $response['messages'][$message->getField()] = $message->getMessage();
        }

        $this->response->setStatusCode(400);
        return $this->response->setJsonContent($response);
    }

    /**
     * @return \Phalcon\Http\ResponseInterface
     * @throws \Phalcon\Mvc\Dispatcher\Exception
     */
    public function deleteAction()
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

        if ($article->delete()) {
            return $this->response->setStatusCode(204);
        }

        throw new \Exception('failed to delete resource');
    }
}
