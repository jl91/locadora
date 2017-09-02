<?php

use Pimple\Container;
use VideoPlace\Model\Database\Connection\ConnectionFactory;
use Pimple\Psr11\Container as ContainerAdapter;

return [
    ConnectionFactory::class => function (Container $container) {
        return new ConnectionFactory(new ContainerAdapter($container));
    },
];