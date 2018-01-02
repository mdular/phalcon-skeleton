<?php

$router = $di->getRouter();

// match the entry page '/'
$router->add('/', 'Index::index');


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
