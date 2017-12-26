<?php

namespace Invo\Modules\Frontend\Controllers;

class IndexController extends ControllerBase
{

    public function indexAction()
    {
        throw new \Exception('Yeah, that failed.');
    }

    public function errorAction()
    {
        $this->response->resetHeaders();
        $this->response->setStatusCode(500);
    }
}
