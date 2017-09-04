<?php

namespace VideoPlace\Model\Database\Service;


use VideoPlace\Model\Database\Entity\UserEntity;

class UserService extends BaseService
{
    public function __construct(\PDO $connection)
    {
        parent::__construct($connection);
    }

    public static function getEntity(): string
    {
        return UserEntity::class;
    }

}