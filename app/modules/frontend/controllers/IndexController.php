<?php

namespace Frontend\Controllers;

class IndexController extends ControllerBase
{

    public function indexAction()
    {
        throw new \Exception('Yeah, that failed.');
    }

    public function error500Action()
    {
        $this->response->resetHeaders();
        $this->response->setStatusCode(500);
    }
}
