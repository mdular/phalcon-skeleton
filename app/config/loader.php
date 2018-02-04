<?php

use Phalcon\Loader;

$loader = new Loader();

/**
 * Register Namespaces
 */
$loader->registerNamespaces([
    'Models'    => APP_PATH . '/common/models/',
    'Library'   => APP_PATH . '/common/library/',
    'Frontend' => APP_PATH . '/modules/frontend/',
    'Cli' => APP_PATH . '/modules/cli/',
]);

/**
 * Register module classes
 */
// $loader->registerClasses([]); <- not needed for now, can be removed

/**
 * Register Files, composer autoloader
 */
$loader->registerFiles([
    'vendor/autoload.php'
]);

$loader->register();
