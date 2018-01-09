<?php

$router = $di->getRouter();

// match the entry page '/'
$router->add('/', 'Index::index');

// match article pages /page/2-infinity
$router->add('/page/([0-9]{2,}|[2-9])', [
    'controller' => 'index',
    'action' => 'index',
    'page' => 1,
]);

// match an article
$router->add('/article/([a-zA-Z0-9_-]+)', [
    'controller' => 'index',
    'action' => 'article',
    'url' => 1,
]);

// examples
$router->add('/site/([a-zA-Z0-9_-]+)', [
    'controller' => 'site',
    'action' => 1,
]);

$router->add('/site2/([a-zA-Z0-9_-]+)', [
    'controller' => 'site',
    'action' => 'showContentFromDatabase',
    'pageName' => 1,
]);

$router->add('/(test)', [
    'controller' => 1,
    'action' => 'index',
]);
