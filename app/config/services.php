<?php

use Phalcon\Loader;
use Phalcon\Mvc\Model\Metadata\Memory as MetaDataAdapter;
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
        'charset'  => $config->database->charset
    ];

    if ($config->database->adapter == 'Postgresql') {
        unset($params['charset']);
    }

    $connection = new $class($params);

    return $connection;
});

/**
 * If the configuration specify the use of metadata adapter use it or use memory otherwise
 */
$di->setShared('modelsMetadata', function () {
    return new MetaDataAdapter();
});
