<?php
namespace Admin\Controller;

use Phalcon\Mvc\Controller;

class ControllerBase extends Controller
{
    public function initialize()
    {
        // set default page title + description
        $this->tag->setTitle('A Great Admin');
        $this->view->setVar('metaDesc', 'Handcrafted with love using Open Source');

        $nav = new \Component\Navigation();
        $nav->addNode('Home', \Admin\Routes::INDEX_INDEX);

        $this->view->setVar('nav', $nav->getNodes());
    }
}
