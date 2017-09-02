<?php

use Pimple\Container;
use VideoPlace\Model\Database\Connection\ConnectionFactory;
use Pimple\Psr11\Container as ContainerAdapter;
use VideoPlace\Model\Database\Service\UserService;

return [
    ConnectionFactory::class => function (Container $container) {
        return new ConnectionFactory(new ContainerAdapter($container));
    },
    UserService::class => function (Container $container) {
        $container = new ContainerAdapter($container);
        $connectionFactory = $container->get(ConnectionFactory::class);
        $connection = $connectionFactory->fabricateConnection();
        return new UserService($connection);
    },
];