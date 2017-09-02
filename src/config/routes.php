<?php

use VideoPlace\Controller;


return [
    'routes' => [
        [
            'pattern' => '/',
            'controller' => Controller\IndexController::class,
            'action' => 'indexAction'
        ]
    ]
];