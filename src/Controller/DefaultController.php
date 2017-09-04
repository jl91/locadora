<?php

namespace VideoPlace\Controller;


use Psr\Container\ContainerInterface;
use VideoPlace\Model\Route\RouteInterface;

abstract class DefaultController implements ControllerInterface
{
    protected $container;
    protected $route;

    public function __construct(ContainerInterface $container, RouteInterface $route)
    {
        $this->container = $container;
        $this->route = $route;
    }

    public function getRoute(): RouteInterface
    {
        return $this->route;
    }

    public function getParams(): array
    {
        return $_REQUEST;
    }

    public function getQuery(): array
    {
        parse_str($_SERVER['QUERY_STRING'], $queryStringAsArray);
        return $queryStringAsArray;
    }

    public function isPost(): bool
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    public function isGet(): bool
    {
        return $_SERVER['REQUEST_METHOD'] === 'GET';
    }

    public function isPut(): bool
    {
        return $_SERVER['REQUEST_METHOD'] === 'PUT';
    }

    public function isDelete(): bool
    {
        return $_SERVER['REQUEST_METHOD'] === 'DELETE';
    }

}