<?php


namespace VideoPlace\Model\Database\Entity;


interface EntityInterface
{
    public function getTableName(): string;

    public function toArray(): array;
}