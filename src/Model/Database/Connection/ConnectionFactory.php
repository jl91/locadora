<?php

namespace VideoPlace\Model\Database\Connection;


use Psr\Container\ContainerInterface;

class ConnectionFactory
{

    private $container = null;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function fabricateConnection(): \PDO
    {
        $configuration = $this->container
            ->get('configuration');

        $dbConfig = $configuration['database'];

        try {
            $connection = $this->getConnectionByProvider($dbConfig['provider'], $dbConfig);
            return $connection;
        } catch (\PDOException $exception) {
            throw new \RuntimeException($exception->getMessage(), 500, $exception->getFile(), $exception->getLine());
        }
    }

    private function getConnectionByProvider(string $provider, array $configuration): \PDO
    {
        if ($provider == 'mysql') {
            return $this->getMysqlConnection($configuration);
        }

        if ($provider == 'postgresql') {
            return $this->getPostgresqlConnection($configuration);
        }

        return FakePDOCOnnection();
    }

    private function getMysqlConnection($configuration)
    {
        $dns = 'mysql:host=%s;dbname=%s';
        return new \PDO(
            sprintf($dns, $configuration['host'], $configuration['dbname']),
            $configuration['username'],
            $configuration['password'],
            $configuration['extra']
        );
    }

    private function getPostgresqlConnection($configuration)
    {
        $dns = 'pgsql:host=%s;port=%s;dbname=%s;user=%s;password=%s';
        return new \PDO(
            sprintf(
                $dns,
                $configuration['host'],
                $configuration['port'],
                $configuration['dbname'],
                $configuration['username'],
                $configuration['password']
            )
        );
    }

}