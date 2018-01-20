<?php

namespace Frontend\Controllers;

class IndexController extends ControllerBase
{

    /**
     * show a paginated list of articles
     */
    public function indexAction(int $page = 1)
    {
        // build a query
        $builder = $this->modelsManager->createBuilder()
                ->from('Models\Article')
                ->where('state = :state:', ['state' => 'published'])
                ->orderBy('published_at DESC');

        // create paginator for query
        $paginator = \Phalcon\Paginator\Factory::load([
            'builder' => $builder,
            'limit'   => 5,
            'page'    => (int) $this->filter->sanitize($page, 'int'),
            'adapter' => 'queryBuilder',
        ]);

        // execute
        $articles = $paginator->getPaginate();

        // 404 empty pages
        if ($page > $articles->total_pages) {
            return $this->dispatcher->forward([
                'controller' => 'index',
                'action' => 'error404',
            ]);
        }

        // pass paginated results to view
        $this->view->setVar('articles', $articles);

        // set page title
        if ($page === 1) {
            $this->view->setVar('metaTitle', 'A Great Thing - Home');
        } else {
            $this->view->setVar('metaTitle', sprintf('A Great Thing - Page %s', $page));
        }
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

        // set page title + description
        $this->view->setVar('metaTitle', sprintf('A Great Thing - %s', $article->getTitle()));
        $this->view->setVar('metaDesc', $article->getExcerpt());
    }

    public function error500Action()
    {
        $this->response->resetHeaders();
        $this->response->setStatusCode(500);
        $this->view->setVar('title', 'A Great Thing - Error');
    }

    public function error404Action()
    {
        $this->response->resetHeaders();
        $this->response->setStatusCode(404);
        $this->view->setVar('title', 'A Great Thing - Not found');
    }
}
