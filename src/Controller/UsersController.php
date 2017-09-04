<?php


namespace VideoPlace\Controller;

use VideoPlace\Model\Database\Entity\EntityInterface;
use VideoPlace\Model\Database\Entity\UserEntity;
use VideoPlace\Model\Database\Service\UserService;
use VideoPlace\Model\View\DefaultViewParser as View;
use VideoPlace\Model\View\Helper\UserTableBodyHelper;

class UsersController extends DefaultController
{
    public function indexAction()
    {
        if (!$this->isGet()) {
            throw new \RuntimeException('Page not found', 404);
        }

        $userService = $this->container
            ->get(UserService::class);

        $data = $userService->findAll();


        $tableBody = UserTableBodyHelper::parse($data);

        return new View('users-index', ['table-body' => $tableBody]);
    }

    public function createAction()
    {
        if (!$this->isPost()) {
            throw new \RuntimeException('Page not found', 404);
        }

        $params = $this->getParams();

        $extraData['createdAt'] = new \DateTimeImmutable('now');

        $data = array_merge($params, $extraData);

        $userEntity = new UserEntity($data);

        $userService = $this->container
            ->get(UserService::class);

        $userService->save($userEntity);

        if ($userEntity->getId() > 0) {
            return header('location: /users');
        }

        throw new \RuntimeException('Something went Wrong on try to create user :(', 500);

    }

    public function readAction()
    {
        if (!$this->isGet()) {
            throw new \RuntimeException('Page not found', 404);
        }

        $userService = $this->container
            ->get(UserService::class);

        $query = (object)$this->getQuery();

        $userEntity = $userService->findOneBy(['id' => $query->id]);

        if ($userEntity instanceof EntityInterface) {
            return new View('users-read', $userEntity->extract());
        }

        throw new \RuntimeException('User Not Found', 404);
    }

    public function updateAction()
    {
        if (!$this->isPost()) {
            throw new \RuntimeException('Page not found', 404);
        }

        $params = $this->getParams();

        $userService = $this->container
            ->get(UserService::class);

        $userEntity = $userService->findOneBy(['id' => $params['id']]);

        $userEntity->__construct($params);

        $userService->save($userEntity);

        if ($userEntity->getId() > 0) {
            return header('location: /users');
        }

        throw new \RuntimeException('Something went Wrong on try to update user :(', 500);

    }

    public function deleteAction()
    {

        if (!$this->isGet()) {
            throw new \RuntimeException('Page not found', 404);
        }
        $userService = $this->container
            ->get(UserService::class);

        $query = (object)$this->getQuery();

        $userEntity = $userService->findOneBy(['id' => $query->id]);

        $result = $userService->delete($userEntity);

        if ($result) {
            return header('location: /users');
        }

        throw new \RuntimeException('Something went Wrong on try to delete user :(', 500);
    }
}