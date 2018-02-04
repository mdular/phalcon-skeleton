<?php

use Phalcon\Loader;
use Phalcon\Mvc\Model\MetaData\Apcu as MetaDataAdapter;
// use Phalcon\Mvc\Model\Metadata\Memory as MetaDataAdapter;
use Phalcon\Config\Adapter\Ini;

/**
 * Shared configuration service
 */
$di->setShared('config', function () {
    $config = new Ini(APP_PATH . '/config/config.ini', INI_SCANNER_NORMAL);

    $instanceConfigPath = sprintf('%1s/config/instance/%2s.ini', APP_PATH, APP_INSTANCE);
    if (is_file($instanceConfigPath)) {
        $config->merge(new Ini($instanceConfigPath, INI_SCANNER_NORMAL));
    }

    $secretsConfigPath = sprintf('%1s/config/secrets.ini', APP_PATH);
    if (is_file($secretsConfigPath)) {
        $config->merge(new Ini($secretsConfigPath, INI_SCANNER_NORMAL));
    }

    return $config;
});

/**
 * Database connection is created based in the parameters defined in the configuration file
 */
$di->setShared('db', function () {
    $config = $this->getConfig();

    $class = 'Phalcon\Db\Adapter\Pdo\\' . $config->database->adapter;
    $params = [
        'host'     => $config->database->host,
        'port'     => $config->database->port,
        'username' => $config->database->username,
        'password' => $config->database->password,
        'dbname'   => $config->database->dbname,
    ];

    $connection = new $class($params);

    if ($config->database->loggingEnabled === true) {
        $eventsManager = new \Phalcon\Events\Manager();
        $logger = new \Phalcon\Logger\Adapter\File($config->database->logPath);
        $eventsManager->attach(
            "db:beforeQuery",
            function (\Phalcon\Events\Event $event, $connection) use ($logger) {
                $sql = $connection->getSQLStatement();

                $logger->log(\Phalcon\Logger::INFO, $sql);
            }
        );
        // Assign the eventsManager to the db adapter instance
        $connection->setEventsManager($eventsManager);
    }

    return $connection;
});

/**
 * If the configuration specify the use of metadata adapter use it or use memory otherwise
 */
$di->setShared('modelsMetadata', function () {
    return new MetaDataAdapter();
});

$di->setShared('logger', function () {
    $config = $this->getConfig()->logger;
    $class = '\Phalcon\Logger\Adapter\\' . $config->adapter;

    return new $class($config->path);
});
