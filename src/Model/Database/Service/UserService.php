<?php

namespace VideoPlace\Model\Database\Service;


class UserService extends BaseService
{
    public function __construct(\PDO $connection)
    {
        parent::__construct($connection);
    }

}