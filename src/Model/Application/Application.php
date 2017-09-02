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
            $message = 'There\'s not configuration to be loaded, application can\'t be initilialized';
            throw new \RuntimeException($message, 500);
        }

        $this->configuration = $container->get('configuration');
        $this->loadRoutes($container);
    }

    private function loadRoutes(Container $container)
    {

        if (empty($this->configuration['routes'])) {
            $message = 'There\'s not routes to be loaded, application can\'t be initilialized';
            throw new \RuntimeException($message, 500);
        }

        $routes = $this->configuration['routes'];


        /**
         * @var route RouteInterface
         */
        foreach ($routes as $route) {

            $controller = $route->getController();
            $action = $route->getAction();

            if (!$controller instanceof ControllerInterface) {
                $message = "Controller {$controller} does not exists";
                throw new \InvalidArgumentException($message, 500);
            }

            if (!method_exists($controller, $action)) {
                $message = "Action {$action} does not exists on controller {$controller}";
                throw new \InvalidArgumentException($message, 500);
            }

        }

        return $this;
    }

    public function run()
    {
        echo "hello World";
    }

}