<?php

namespace VideoPlace\Controller;


use VideoPlace\Model\View\DefaultViewParser as View;


class IndexController extends DefaultController
{
    public function indexAction()
    {
        return new View('index', ['content' => 'Hello World']);
    }
}