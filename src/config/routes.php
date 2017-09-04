<?php

use VideoPlace\Controller;


return [
    'routes' => [
        [
            'pattern' => '/',
            'controller' => Controller\IndexController::class,
            'action' => 'indexAction'
        ],
        [
            'pattern' => '/users',
            'controller' => Controller\UsersController::class,
            'action' => 'indexAction'
        ],
        [
            'pattern' => '/users/create',
            'controller' => Controller\UsersController::class,
            'action' => 'createAction'
        ],
        [
            'pattern' => '/users/update',
            'controller' => Controller\UsersController::class,
            'action' => 'updateAction'
        ],
        [
            'pattern' => '/users/delete',
            'controller' => Controller\UsersController::class,
            'action' => 'deleteAction'
        ],
    ],
];