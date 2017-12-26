<?php

use Phalcon\Loader;

$loader = new Loader();

/**
 * Register Namespaces
 */
$loader->registerNamespaces([
    'Invo\Models' => APP_PATH . '/common/models/',
    'Invo'        => APP_PATH . '/common/library/',
]);

/**
 * Register module classes
 */
$loader->registerClasses([
    'Invo\Modules\Frontend\Module' => APP_PATH . '/modules/frontend/Module.php',
    'Invo\Modules\Cli\Module'      => APP_PATH . '/modules/cli/Module.php'
]);

/**
 * Register Files, composer autoloader
 */
$loader->registerFiles([
    'vendor/autoload.php'
]);

$loader->register();
