<?php

namespace VideoPlace\Model\Route;

use Psr\Container\ContainerInterface;
use VideoPlace\Controller\ControllerInterface;

interface RouteInterface
{

    public function __construct(string $pattern, string $controller, string $action);

    public function getPattern(): string;

    public function getController(ContainerInterface $container): ControllerInterface;

    public function getAction(): string;

}