<?php

use VideoPlace\Model\Application\Application;
use Pimple\Container;
use Pimple\Psr11\Container as Psr11Adapter;


try {

    define('DS', DIRECTORY_SEPARATOR);
    define('APP_ROOT', realpath(
        implode(DS, [
            __DIR__,
            '..'
        ])
    ));

    require_once APP_ROOT . DS . 'vendor' . DS . 'autoload.php';

    $configurations = require_once APP_ROOT . DS . 'src' . DS . 'config' . DS . 'config.global.php';
    $routes = require_once APP_ROOT . DS . 'src' . DS . 'config' . DS . 'routes.php';
    $di = require_once APP_ROOT . DS . 'src' . DS . 'config' . DS . 'di.php';

    $containerData = array_merge($configurations, $routes, $di);

    $container = new Container($containerData);
    $containerAdapter = new Psr11Adapter($container);

    $application = new Application($containerAdapter);

    $application->run();

} catch (\Throwable $throwable) {
    http_response_code($throwable->getCode());
    echo $throwable->getMessage();
    exit();
}