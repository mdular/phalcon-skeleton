<?php

namespace Frontend\Controllers;

class PageController extends ControllerBase
{
    public function showAction()
    {
        $page = $this->dispatcher->getParam('page', 'string');
        $view = sprintf('page/%s', $page);

        if ($this->view->exists($view)) {
            $this->view->pick($view);
            $this->tag->prependTitle(sprintf('%s - ', ucfirst($page)));
            return true;
        }

        // if content does not exist, show 404
        throw new \Phalcon\Mvc\Dispatcher\Exception('Resource unavailable', \Phalcon\Dispatcher::EXCEPTION_ACTION_NOT_FOUND);
    }
}
