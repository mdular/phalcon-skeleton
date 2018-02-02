<?php

namespace Frontend\Controllers;

class PageController extends ControllerBase
{
    public function showAction()
    {
        $view = sprintf('page/%s', $this->dispatcher->getParam('page'));

        if ($this->view->exists($view)) {
            $this->view->pick($view);
            return true;
        }

        // if content does not exist, show 404
        throw new \Phalcon\Mvc\Dispatcher\Exception('Resource unavailable', \Phalcon\Dispatcher::EXCEPTION_ACTION_NOT_FOUND);
    }
}
