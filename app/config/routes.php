<?php

$router = $di->getRouter();

// match the entry page '/'
$router->add('/', 'Index::index');

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
