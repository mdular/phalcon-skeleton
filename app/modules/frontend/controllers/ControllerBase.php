<?php
namespace Frontend\Controller;

use Phalcon\Mvc\Controller;

class ControllerBase extends Controller
{
    public function initialize()
    {
        // set default page title + description
        $this->tag->setTitle('A Great Thing');
        $this->view->setVar('metaDesc', 'Handcrafted with love using Open Source');
    }
}
