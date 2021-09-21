<?php

namespace Simple\Core\Database;

class ConnectionFactory
{
    private array $configrations;

    private string $driver;

    public function __construct(array $configrations, string $driver)
    {
        $this->configrations = $configrations;
        $this->driver = $driver;
    }

    public function makeConnection(): Connection
    {
        switch ($this->dirver) {
            case 'mysql':
                return MySqlConnection::getInstance($this->configrations[$this->driver]);
                break;
        }
    }
}
