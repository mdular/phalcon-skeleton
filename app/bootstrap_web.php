<?php

use Phalcon\Di\FactoryDefault;
use Phalcon\Mvc\Application;

define('DEBUG', getenv('DEBUG') === 'true' ? true : false);
define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');

/**
 * Include Autoloader
 */
include APP_PATH . '/config/loader.php';

/**
* Error handling
*/
Phalcon\Error\Handler::register();

if (DEBUG === true) {
    $debug = new Phalcon\Debug();
    $debug->listen();
}

/**
 * The FactoryDefault Dependency Injector automatically registers the services that
 * provide a full stack framework. These default services can be overidden with custom ones.
 */
$di = new FactoryDefault();

/**
 * Include general services
 */
require APP_PATH . '/config/services.php';

/**
 * Include web environment specific services
 */
require APP_PATH . '/config/services_web.php';

/**
 * Get config service for use in inline setup below
 */
$config = $di->getConfig();

/**
 * Handle the request
 */
$application = new Application($di);

/**
 * Register application modules
 */
$application->registerModules([
    'frontend' => ['className' => 'Invo\Modules\Frontend\Module'],
]);

/**
 * Include routes
 */
require APP_PATH . '/config/routes.php';

// echo str_replace(["\n","\r","\t"], '', $application->handle()->getContent());
echo $application->handle()->getContent();
