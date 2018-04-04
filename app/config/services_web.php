<?php

use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\Router;
use Phalcon\Mvc\Url as UrlResolver;
use Phalcon\Session\Adapter\Files as SessionAdapter;
use Phalcon\Mvc\View;
use Phalcon\Mvc\View\Engine\Volt as VoltEngine;
use Phalcon\Flash\Direct as Flash;

/**
 * Registering a router
 */
$di->setShared('router', function () {
    $router = new Router(false);
    $router->setDefaultModule(APP_MODULE);
    $router->setDefaultNameSpace(sprintf('%s\Controller', ucfirst(APP_MODULE)));
    $router->notFound('Index::error404');

    $config = $this->getConfig();
    $router->mount(new Frontend\Routes($config->modules->frontend->hostname));
    $router->mount(new Admin\Routes($config->modules->admin->hostname));

    return $router;
});

/**
 * The URL component is used to generate all kinds of URLs in the application
 */
$di->setShared('url', function () {
    $config = $this->getConfig();

    $url = new UrlResolver();
    $url->setBaseUri($config->application->baseUri);

    return $url;
});

/**
 * Starts the session the first time some component requests the session service
 */
$di->setShared('session', function () {
    $session = new SessionAdapter();
    $session->start();

    return $session;
});

/**
 * Register the session flash service with the Twitter Bootstrap classes
 */
$di->set('flash', function () {
    return new Flash([
        'error'   => 'alert alert-danger',
        'success' => 'alert alert-success',
        'notice'  => 'alert alert-info',
        'warning' => 'alert alert-warning'
    ]);
});

/**
* Registering the dispatcher
*/
$di->setShared('dispatcher', function() use ($di) {
    $dispatcher = new Dispatcher();

    $eventsManager = $di->getShared('eventsManager');
    $eventsManager->attach('dispatch:beforeException', function (Phalcon\Events\Event $e, $dispatcher, \Throwable $exception) {

        if ($exception instanceof \Phalcon\Mvc\Dispatcher\Exception === false) {
            return;
        }

        switch($exception->getCode()){
            case Phalcon\Dispatcher::EXCEPTION_ACTION_NOT_FOUND:
            case Phalcon\Dispatcher::EXCEPTION_HANDLER_NOT_FOUND:
                return $dispatcher->forward([
                    'controller' => 'index',
                    'action'    => 'error404'
                ]);
            // case Phalcon\Mvc\Dispatcher::EXCEPTION_INVALID_HANDLER:
            // case Phalcon\Mvc\Dispatcher::EXCEPTION_INVALID_PARAMS:
            //     $response = $dispatcher->getDi()->getShared('response');
            //     $response->setStatusCode(400);
            //     $response->sendHeaders();
            //     return false;
        }
    });
    $dispatcher->setEventsManager($eventsManager);

    return $dispatcher;
});
