<?php

use Phalcon\Loader;

$loader = new Loader();

/**
 * Register Namespaces
 */
$loader->registerNamespaces([
    'Models'    => APP_PATH . '/common/models/',
    'Library'   => APP_PATH . '/common/library/',
]);

/**
 * Register module classes
 */
$loader->registerClasses([
    'Frontend\Module' => APP_PATH . '/modules/frontend/Module.php',
    'Cli\Module'      => APP_PATH . '/modules/cli/Module.php'
]);

/**
 * Register Files, composer autoloader
 */
$loader->registerFiles([
    'vendor/autoload.php'
]);

$loader->register();
