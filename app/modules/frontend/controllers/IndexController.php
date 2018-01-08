<?php

namespace Frontend\Controllers;

class IndexController extends ControllerBase
{

    public function indexAction()
    {
        // throw new \Exception('Yeah, that failed.');
    }

    /**
     * show an article by its URL
     */
    public function articleAction(string $url)
    {
        // find one article where the url matches the input and state = published
        $article = \Models\Article::findFirst([
            'conditions' => 'url = ?1 AND state = ?2',
            'bind' => [
                1 => $this->filter->sanitize($url, 'string'),
                2 => 'published',
            ],
        ]);

        // if no article was found, return 404 Not found
        if (!$article) {
            return $this->dispatcher->forward([
                'controller' => 'index',
                'action' => 'error404',
            ]);
        }

        // pass the article to the view
        $this->view->setVar('article', $article);
    }

    public function error500Action()
    {
        $this->response->resetHeaders();
        $this->response->setStatusCode(500);
    }

    public function error404Action()
    {
        $this->response->resetHeaders();
        $this->response->setStatusCode(404);
    }
}
