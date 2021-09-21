<?php

namespace Simple\Core\Database;

abstract class Connection
{

    protected static \PDO $pdo;

    protected array $configrations;

    protected static Connection $connection;

    protected function __construct(array $configrations)
    {
        $this->configrations = $configrations;
    }

    abstract public static function getInstance(array $configrations = []): Connection;

    abstract public function connect();

    abstract protected function getDsn(): string;
}
