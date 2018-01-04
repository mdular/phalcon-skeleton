<?php

use Phalcon\Di\FactoryDefault;
use Phalcon\Mvc\Application;

define('ENV_PRODUCTION', 'production');
define('APPLICATION_ENV', getenv('APPLICATION_ENV') ?: ENV_PRODUCTION);
define('DEBUG', getenv('DEBUG') === 'true' ? true : false);
define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');
define('APP_MODULE', getenv('APP_MODULE') ?: 'frontend');
define('APP_INSTANCE', getenv('APP_INSTANCE') ?: '');

/**
 * Include Autoloader
 */
require_once(APP_PATH . '/config/loader.php');

/**
 * The FactoryDefault Dependency Injector automatically registers the services that
 * provide a full stack framework. These default services can be overidden with custom ones.
 */
$di = new FactoryDefault();

/**
* Error handling
* requires autoloader (for Handler class)
* requires $di from FactoryDefault to be present (url service)
*/
if (DEBUG === true) {
    $debug = new Phalcon\Debug();
    // the 2nd argument will turn on capturing of silent errors, such as warnings
    $debug->listen(true, true);
} else {
    // TODO: this handler is not catching / logging warnings.. because it disables error_reporting
    Phalcon\Error\Handler::register();
}

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
    'frontend' => ['className' => 'Frontend\Module'],
]);

/**
 * Include routes
 */
require APP_PATH . '/config/routes.php';

/**
 * Handle the request (dispatch), send response
 */
$response = $application->handle();
$response->send();
