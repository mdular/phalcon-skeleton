<?php

use Phalcon\Loader;

$loader = new Loader();

/**
 * Register Namespaces
 */
$loader->registerNamespaces([
    'Models'    => sprintf('%s/common/models/', APP_PATH),
    'Library'   => sprintf('%s/common/library/', APP_PATH),
    'Frontend'  => sprintf('%s/modules/frontend/', APP_PATH),
    'Cli'       => sprintf('%s/modules/cli/', APP_PATH),
]);

/**
 * Register module classes
 */
// $loader->registerClasses([]); <- not needed currently

/**
 * Register Files, composer autoloader
 */
$loader->registerFiles([
    'vendor/autoload.php'
]);

$loader->register();
