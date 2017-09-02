<?php

namespace VideoPlace\Model\Application;

use Psr\Container\ContainerInterface as Container;
use VideoPlace\Model\Route\DefaultRoute;
use VideoPlace\Controller\ControllerInterface;
use VideoPlace\Model\Route\RouteInterface;

class Application
{
    /**
     * @var null|Container
     */
    private $container = null;

    private $configuration = null;
    /**
     * @var ControllerInterface[]
     */
    private $registeredControllers = [];

    public function __construct(Container $container)
    {
        $this->container = $container;

        if (!$container->has('configuration')) {
            $message = 'There\'s no configuration to be loaded, application can\'t be initialized';
            throw new \RuntimeException($message, 500);
        }

        $this->configuration = $container->get('configuration');
        $this->loadRoutes($container);
    }

    /**
     * @param Container $container
     * @return $this
     * @todo Melhorar carreganebti de rotas, implementar lazy load para instanciar o controller apenas quando for
     * necessário
     */
    private function loadRoutes(Container $container)
    {
        $routes = $container->get('routes');
        if (!$container->has('routes') || empty($routes)) {
            $message = 'There\'s no routes to be loaded, application can\'t be initialized';
            throw new \RuntimeException($message, 500);
        }

        /**
         * @var route RouteInterface
         */
        foreach ($routes as $route) {

            $route = (object)$route;
            $currentRoute = new DefaultRoute($route->pattern, $route->controller, $route->action);

            $controller = $currentRoute->getController($container);
            $action = $currentRoute->getAction();

            if (!$controller instanceof ControllerInterface) {
                $message = "Controller {$controller} does not exists, Page Not Found";
                throw new \InvalidArgumentException($message, 404);
            }

            if (!method_exists($controller, $action)) {
                $message = "Action {$action} does not exists on controller {$controller}";
                throw new \InvalidArgumentException($message, 404);
            }

            $this->registeredControllers[] = $controller;
        }

        return $this;
    }

    private function getRequestRoute(): RouteInterface
    {
        $uri = $_SERVER['REQUEST_URI'];
        $pieces = explode('?', $uri);
        $path = $pieces[0];
        $basePattern = '/%s/';

        foreach ($this->registeredControllers as $registeredController) {
            $route = $registeredController->getRoute();
            $pattern = sprintf($basePattern, urlencode($path));
            $subject = sprintf($basePattern, urlencode($route->getPattern()));

            $matches = preg_match($pattern, $subject);
            if ($matches === 1) {
                return $route;
            }
        }

        $message = "Page Not Found";
        throw new \InvalidArgumentException($message, 404);

    }

    public function run()
    {
        $route = $this->getRequestRoute();
        $controller = $route->getController($this->container);
        $action = $route->getAction();
        echo $controller->$action();
    }

}