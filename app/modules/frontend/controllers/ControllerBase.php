<?php
namespace Frontend\Controller;

use Phalcon\Mvc\Controller;

class ControllerBase extends Controller
{
    public function beforeExecuteRoute()
    {
        if ($this->request->isPost()) {
            // null, null -> will trigger default behaviour, removeData false -> will NOT delete the values - USE HTTPS
            if ($this->security->checkToken(null, null, false) === false) {
                $this->logger->log(\Phalcon\Logger::ALERT, sprintf('CSRF forgery detected - %1s - %2s - %3s',
                    $this->request->getURI(),
                    $this->request->getClientAddress(),
                    $this->request->getHTTPReferer())
                );

                $this->session->destroy(true);

                $this->response->resetHeaders();
                $this->response->setStatusCode(403);
                $this->response->send();

                exit();
            }
        }
    }

    public function initialize()
    {
        // set default page title + description
        $this->tag->setTitle('A Great Thing');
        $this->view->setVar('metaDesc', 'Handcrafted with love using Open Source');
    }
}
