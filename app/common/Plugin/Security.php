<?php

namespace Plugin;

use Phalcon\Events\Event;
use Phalcon\Mvc\User\Plugin;
use Phalcon\Mvc\Dispatcher;

class Security extends Plugin
{
    public function beforeExecuteRoute(Event $event, Dispatcher $dispatcher)
    {
        $isApiRequest = false;
        if ($this->router->wasMatched()) {
            $isApiRequest = preg_match('/\/api\/v1/', $this->router->getMatchedRoute()->getPattern());
        }

        // API requests: exit immediately on failed api token check
        if ($isApiRequest === true && $this->apiTokenCheck() === false) {
            exit();
        }

        // Non-API requests: exit immediately on failed csrf check
        if ($isApiRequest === false && $this->csrfCheck() === false) {
            exit();
        }

        // check access control list
        if ($this->accessCheck($dispatcher) === true) {
            return true;
        }

        // show the login form if not logged in
        if ($this->isLoggedIn() === false) {
            $this->response->resetHeaders();
            $this->response->setStatusCode(403);
            return $dispatcher->forward([
                'controller' => 'index',
                'action' => 'login',
            ]);
            return false;
        }

        return $dispatcher->forward([
            'controller' => 'index',
            'action' => 'error403',
        ]);
    }

    public function isLoggedIn():bool
    {
        return $this->session->get('auth') !== null;
    }

    protected function accessCheck(Dispatcher $dispatcher):bool
    {
        // get current user role from auth or use default ('guest')
        $auth = $this->session->get('auth');
        $role = $auth['role'] ?  : $this->acl->getDefaultRole();

        // get request target
        $controller = $dispatcher->getControllerName();
        $action = $dispatcher->getActionName();

        if (DEBUG) {
            $this->logger->info($role);
            $this->logger->info($controller);
            $this->logger->info($action);
            $this->logger->info((string) $this->acl->isAllowed($role, $controller, $action));
        }

        return $this->acl->isAllowed($role, $controller, $action);
    }

    protected function apiTokenCheck():bool
    {
        // TODO: implement
        return true;
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
