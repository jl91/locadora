<?php

namespace VideoPlace\Controller;

use Psr\Container\ContainerInterface;
use VideoPlace\Model\Route\RouteInterface;

interface ControllerInterface
{
    public function __construct(ContainerInterface $container, RouteInterface $route);

    public function getRoute() : RouteInterface;

    public function getParams(): array;

    public function getQuery(): array;

    public function isPost(): bool;

    public function isGet(): bool;

    public function isPut(): bool;

    public function isDelete(): bool;

}