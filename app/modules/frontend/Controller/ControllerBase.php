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

        $nav = new \Component\Navigation();
        $nav->addNode('Home', \Frontend\Routes::INDEX_INDEX, [], [\Frontend\Routes::INDEX_PAGE])
            ->addNode('About', \Frontend\Routes::PAGE_SHOW, [
                'page' => 'about',
            ])
            ->addNode('Contact', \Frontend\Routes::PAGE_CONTACT);

        $this->view->setVar('nav', $nav->getNodes());
    }
}
