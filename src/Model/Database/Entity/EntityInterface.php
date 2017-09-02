<?php


namespace VideoPlace\Model\Database\Entity;


interface EntityInterface
{
    public function getIdColumnName(): string;

    public function getTableName(): string;

    public function toArray(): array;
}