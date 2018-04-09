<?php

namespace Admin;

use Phalcon\DiInterface;
use Phalcon\Loader;
use Phalcon\Mvc\View;
use Phalcon\Mvc\View\Engine\Php as PhpEngine;
use Phalcon\Mvc\ModuleDefinitionInterface;
use Phalcon\Config\Adapter\Ini;
use Phalcon\Config;

class Module implements ModuleDefinitionInterface
{
    /**
     * Registers an autoloader related to the module
     *
     * @param DiInterface $di
     */
    public function registerAutoloaders(DiInterface $di = null)
    {
        // not needed for now
        // $loader = new Loader();
        //
        // $loader->registerNamespaces([
        //     'Admin\Controllers' => __DIR__ . '/controllers/',
        //     'Admin\Models'      => __DIR__ . '/models/'
        // ]);
        //
        // $loader->register();
    }

    /**
     * Registers services related to the module
     *
     * @param DiInterface $di
     */
    public function registerServices(DiInterface $di)
    {
        /**
         * Try to load local configuration
         */
        if (file_exists(__DIR__ . '/config/config.ini')) {

            $config = $di['config'];

            $override = new Ini(__DIR__ . '/config/config.ini');

            if ($config instanceof Config) {
                $config->merge($override);
            } else {
                $config = $override;
            }
        }

        /**
         * Setting up the view component
         */
        $di['view'] = function () {
            $config = $this->getConfig();

            $view = new View();
            $view->setViewsDir($config->get('application')->viewsDir);

            $view->registerEngines([
                '.phtml' => PhpEngine::class
            ]);

            return $view;
        };

        $di->setShared('acl', function () {
            $ruleSet = new Ruleset();
            return $ruleSet;
        });
    }
}
