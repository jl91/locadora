<?php


namespace VideoPlace\Model\Database\Service;


use VideoPlace\Model\Database\Entity\EntityInterface;

interface ServiceInterface
{

    public function __construct(\PDO $connection);

    public function persist(EntityInterface $entity): bool;

    public function save(EntityInterface $entity): bool;

    public function merge(EntityInterface $entity): bool;

    public function delete(EntityInterface $entity): bool;

    public function findBy(array $data = [], EntityInterface $entity): array;

    public function findAll(EntityInterface $entity): array;

}