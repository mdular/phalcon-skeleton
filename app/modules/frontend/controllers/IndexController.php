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
            // if content does not exist, show 404
            throw new \Phalcon\Mvc\Dispatcher\Exception('Resource unavailable', \Phalcon\Dispatcher::EXCEPTION_ACTION_NOT_FOUND);
        }

        // pass paginated results to view
        $this->view->setVar('articles', $articles);

        // set page title
        if ($page === 1) {
            $this->tag->prependTitle('Home - ');
        } else {
            $this->tag->prependTitle(sprintf('Page %s - ', $page));
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
            // if content does not exist, show 404
            throw new \Phalcon\Mvc\Dispatcher\Exception('Resource unavailable', \Phalcon\Dispatcher::EXCEPTION_ACTION_NOT_FOUND);
        }

        // pass the article to the view
        $this->view->setVar('article', $article);

        // set page title + description
        $this->tag->prependTitle(sprintf('%s - ', $article->getTitle()));
        $this->view->setVar('metaDesc', $article->getExcerpt());
    }

    public function error500Action()
    {
        $this->response->resetHeaders();
        $this->response->setStatusCode(500);
        $this->tag->prependTitle('Error - ');
    }

    public function error404Action()
    {
        $this->response->resetHeaders();
        $this->response->setStatusCode(404);
        $this->tag->prependTitle('Not found - ');
    }
}
