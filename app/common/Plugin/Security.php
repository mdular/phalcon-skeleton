<?php

namespace Plugin;

use Phalcon\Events\Event;
use Phalcon\Mvc\User\Plugin;
use Phalcon\Mvc\Dispatcher;

class Security extends Plugin
{
    public function beforeExecuteRoute(Event $event, Dispatcher $dispatcher)
    {
        if ($this->csrfCheck() === false) {
            exit();
        }
    }

    protected function csrfCheck():bool
    {
        // no checking unless the request method = POST
        if ($this->request->isPost() === false) {
            return true;
        }

        // check the token
        // null, null -> will trigger default behaviour, removeData false ->
        // this will NOT delete the values: the token stays valid (multiple tabs) - USE HTTPS
        if ($this->security->checkToken(null, null, false) === true) {
            return true;
        }

        $this->logger->log(\Phalcon\Logger::ALERT, sprintf('CSRF forgery detected - %1s - %2s - %3s',
            $this->request->getURI(),
            $this->request->getClientAddress(),
            $this->request->getHTTPReferer())
        );

        $this->session->destroy(true);

        $this->response->resetHeaders();
        $this->response->setStatusCode(403);
        $this->response->send();

        return false;
    }
}
