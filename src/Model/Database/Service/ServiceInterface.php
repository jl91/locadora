<?php


namespace VideoPlace\Model\Database\Service;


use VideoPlace\Model\Database\Entity\EntityInterface;

interface ServiceInterface
{

    public function __construct(\PDO $connection);

    public function persist(EntityInterface $entity): EntityInterface;

    public function save(EntityInterface $entity): EntityInterface;

    public function merge(EntityInterface $entity): EntityInterface;

    public function delete(EntityInterface $entity): bool;

    public function findOneBy(array $data = []): EntityInterface;

    public function findBy(array $data = []): array;

    public function findAll(): array;

    public static function getEntity(): string;

}