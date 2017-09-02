<?php

namespace VideoPlace\Model\Database\Service;


use VideoPlace\Model\Database\Entity\EntityInterface;

abstract class BaseService implements ServiceInterface
{
    const INSERT_QUERY = "INSERT INTO %s (%s) VALUES (%s)";
    const SELECT_QUERY = "SELECT %s FROM %s ";
    const SELECT_WHERE_QUERY = "WHERE %s ";
    const SELECT_AND_WHERE_QUERY = "AND %s ";
    const DELETE_QUERY = "DELETE FROM %s " . self::SELECT_WHERE_QUERY;
    const UPDATE_QUERY = "UPDATE %s SET %s " . self::SELECT_WHERE_QUERY;


    public $connection = null;

    public function __construct(\PDO $connection)
    {
        $this->connection = $connection;
    }

    public function persist(EntityInterface $entity): bool
    {
        $data = $entity->toArray();

        if (!empty($data[$entity->getIdColumnName()])) {
            return $this->merge($entity);
        }

        return $this->save($entity);
    }

    public function save(EntityInterface $entity): bool
    {
        $data = $entity->toArray();

        $columns = array_keys($data);

        $columnsName = implode(', ', $columns);

        foreach ($columns as $key => $column) {
            $columns[$key] = ":{$column}";
        }

        $values = implode(', ', $columns);

        $query = sprintf(static::INSERT_QUERY, $entity->getTableName(), $columnsName, $values);

        $statement = $this->connection
            ->prepare($query);

        return $statement->execute($data);

    }

    public function merge(EntityInterface $entity): bool
    {

    }

    public function delete(EntityInterface $entity): bool
    {

    }

    public function findBy(array $data = [], EntityInterface $entity): array
    {

        $pattern = static::SELECT_QUERY . static::SELECT_WHERE_QUERY;
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

        $query = sprintf($pattern, $columns, $entity->getTableName(), '1');


        $statement = $this->connection
            ->prepare($query);


        $result = [];

        if (
            $statement->execute($data) &&
            $statement->rowCount()
        ) {
            $result = $statement->fetchAll(\PDO::FETCH_CLASS, get_class($entity));
        }

        return $result;
    }

    public function findAll(EntityInterface $entity): array
    {
        return $this->findBy([], $entity);
    }


}