<?php

namespace VideoPlace\Controller;

use VideoPlace\Model\Database\Entity\UserEntity;
use VideoPlace\Model\View\DefaultViewParser as View;
use VideoPlace\Model\Database\Service\UserService;

class IndexController extends DefaultController
{
    public function indexAction()
    {
        return new View('index', ['content' => 'Hello World']);
    }
}