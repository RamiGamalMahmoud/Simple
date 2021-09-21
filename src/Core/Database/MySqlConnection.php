<?php

namespace Simple\Core\Database;

class MySqlConnection extends Connection
{

    public static function getInstance(array $configrations = []): Connection
    {
        if (!isset(self::$connection)) {
            self::$connection = new MySqlConnection($configrations);
        }
        return self::$connection;
    }

    public function connect()
    {
        if (!isset(self::$pdo)) {
            self::$pdo = new \PDO(
                $this->getDsn(),
                $this->configrations['user_name'],
                $this->configrations['password']
            );
        }
    }

    protected function getDsn(): string
    {
        $dsn = $this->configrations['driver'] .
            ':host=' .
            $this->configrations['host'] .
            ';dbname=' .
            $this->configrations['db'];
        return $dsn;
    }
}
