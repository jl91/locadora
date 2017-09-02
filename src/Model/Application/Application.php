<?php

namespace VideoPlace\Model\Application;

use Psr\Container\ContainerInterface as Container;

class Application
{
    private $container = null;
    private $configuration = null;

    public function __construct(Container $container)
    {
        $this->container = $container;

        if (!$container->has('configuration')) {
            throw new \RuntimeException('There\'s not configuration to load, application can\'t be initilialized', 500);
        }

        $this->configuration = $container->get('configuration');
        $this->loadRoutes($container);
    }

    private function loadRoutes(Container $container)
    {

        if (empty($this->configuration['routes'])) {
            throw new \RuntimeException('There\'s not routes to load, application can\'t be initilialized', 500);
        }

        $routes = $this->configuration['routes'];


        foreach ($routes as $route) {

        }

        return $this;
    }

    public function run()
    {
        echo "hello World";
    }

}