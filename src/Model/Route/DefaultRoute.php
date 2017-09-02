<?php

namespace VideoPlace\Model\Route;

use Psr\Container\ContainerInterface;
use VideoPlace\Controller\ControllerInterface;

class DefaultRoute implements RouteInterface
{

    private $pattern = null;
    private $controller = null;
    private $action = null;

    public function __construct(string $pattern, string $controller, string $action)
    {
        $this->pattern = $pattern;
        $this->controller = $controller;
        $this->action = $action;
    }

    public function getPattern(): string
    {
        return $this->pattern;
    }

    public function getController(ContainerInterface $container): ControllerInterface
    {
        return new $this->controller($container, $this);
    }

    public function getAction(): string
    {
        return $this->action;
    }
}