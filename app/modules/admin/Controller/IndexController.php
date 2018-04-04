<?php

namespace Admin\Controller;

class IndexController extends ControllerBase
{
    public function indexAction()
    {
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
