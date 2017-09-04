<?php

namespace VideoPlace\Model\Database\Service;


use VideoPlace\Model\Database\Entity\EntityInterface;

abstract class BaseService implements ServiceInterface
{
    const INSERT_QUERY = "INSERT INTO %s (%s) VALUES (%s)";
    const SELECT_QUERY = "SELECT %s FROM %s ";
    const WHERE_QUERY = "WHERE %s ";
    const LIMIT_QUERY = "LIMIT %d ";
    const SELECT_AND_WHERE_QUERY = "AND %s ";
    const DELETE_QUERY = "DELETE FROM %s " . self::WHERE_QUERY;
    const UPDATE_QUERY = "UPDATE %s SET %s " . self::WHERE_QUERY;


    public $connection = null;

    public function __construct(\PDO $connection)
    {
        $this->connection = $connection;
    }

    public function save(EntityInterface $entity): EntityInterface
    {
        $data = $entity->toArray();

        if (!empty($data[$entity->getIdColumnName()])) {
            return $this->merge($entity);
        }

        return $this->persist($entity);
    }

    public function persist(EntityInterface $entity): EntityInterface
    {
        $data = $entity->extract();

        $columns = array_keys($data);

        $columnsName = implode(', ', $columns);

        foreach ($columns as $key => $column) {
            $columns[$key] = ":{$column}";
        }

        $values = implode(', ', $columns);

        $query = sprintf(static::INSERT_QUERY, $entity->getTableName(), $columnsName, $values);

        $statement = $this->connection
            ->prepare($query);

        $statement->execute($data);

        $idColumnName = $entity->getIdColumnName();

        $lastInsertedId = $this->connection
            ->lastInsertId();

        $entity->{'set' . ucfirst($idColumnName)}($lastInsertedId);

        return $entity;

    }

    public function merge(EntityInterface $entity): EntityInterface
    {

        $data = $entity->toArray();
        $idColumnName = $entity->getIdColumnName();
        $id = $data[$idColumnName];

        $databaseEntity = $this->findOneBy(['id' => $id]);

        if (!$databaseEntity instanceof EntityInterface) {
            throw new \RuntimeException("Entity not found on database", 500);
        }

        //PDO não returna true se o valor de update for idêntico ao do banco de dados
        if ($databaseEntity->toArray() === $data) {
            return true;
        }
        $databaseEntity->__construct($data);

        $data = $databaseEntity->extract();

        $pattern = static::UPDATE_QUERY;

        $fieldsToUpdate = [];

        foreach ($data as $key => $value) {
            $fieldsToUpdate[] = $key . ' = :' . $key;
        }

        $fieldsToUpdate = implode(', ', $fieldsToUpdate);

        $whereClause = $idColumnName . ' = :' . $idColumnName;

        $query = sprintf($pattern, $entity->getTableName(), $fieldsToUpdate, $whereClause);

        $statement = $this->connection
            ->prepare($query);

        $statement->execute($data);

        return $databaseEntity;
    }

    public function delete(EntityInterface $entity): bool
    {
        $idColumnName = $entity->getIdColumnName();

        $pattern = static::DELETE_QUERY;

        $query = sprintf($pattern, $entity->getTableName(), $idColumnName . ' = :' . $idColumnName);

        $statement = $this->connection
            ->prepare($query);

        $id = $entity->toArray()[$idColumnName];

        $statement->execute([$idColumnName => $id]);

        return $statement->rowCount() > 0;
    }

    /**
     * @param array $data
     * @return array
     * @todo, métodos de busca devem ser movidos para um repository
     */
    public function findBy(array $data = []): array
    {

        $pattern = static::SELECT_QUERY . static::WHERE_QUERY;
        $columns = '*';

        if (empty($data)) {
            $pattern = static::SELECT_QUERY;
        } else {
            $keys = array_keys($data);
            $andWhere = [];
            foreach ($keys as $columnValue) {
                $andWhere[] = sprintf(static::SELECT_AND_WHERE_QUERY, "{$columnValue} = :{$columnValue} ");
            }

            $pattern .= implode(' ', $andWhere);
        }

        $entityClass = static::getEntity();

        $entity = new $entityClass();

        $query = sprintf($pattern, $columns, $entity->getTableName(), '1');


        $statement = $this->connection
            ->prepare($query);

        $result = [];

        if (
            $statement->execute($data) &&
            $statement->rowCount() > 0
        ) {
            $result = $statement->fetchAll(\PDO::FETCH_CLASS, $entityClass);
        }

        return $result;
    }

    public function findOneBy(array $data = []): EntityInterface
    {

        $pattern = static::SELECT_QUERY . static::WHERE_QUERY;
        $columns = '*';

        if (empty($data)) {
            $pattern = static::SELECT_QUERY;
        } else {
            $keys = array_keys($data);
            $andWhere = [];
            foreach ($keys as $columnValue) {
                $andWhere[] = sprintf(static::SELECT_AND_WHERE_QUERY, "{$columnValue} = :{$columnValue} ");
            }

            $pattern .= implode(' ', $andWhere);
        }

        $pattern .= sprintf(static::LIMIT_QUERY, 1);

        $entityClass = static::getEntity();

        $entity = new $entityClass();

        $query = sprintf($pattern, $columns, $entity->getTableName(), '1');


        $statement = $this->connection
            ->prepare($query);

        $result = [];

        if (
            $statement->execute($data) &&
            $statement->rowCount() > 0
        ) {
            $result = $statement->fetchObject($entityClass);
        }

        return $result;
    }

    /**
     * @return array
     * @todo, métodos de busca devem ser movidos para um repository
     */
    public function findAll(): array
    {
        return $this->findBy([]);
    }

}