<?php

$router = $di->getRouter();

// match the entry page '/'
$router->add('/', 'Index::index');

