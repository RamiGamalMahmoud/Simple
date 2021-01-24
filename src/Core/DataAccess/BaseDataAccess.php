<?php

namespace Simple\Core\DataAccess;

abstract class BaseDataAccess implements IDataAccess
{
    protected static \PDO $conn;
    protected static $db;
    protected static $driver;
    protected static $host;
    protected static $password;
    protected static $port;
    protected static $userName;

    protected static function getConnectionString(): string
    {
        return self::$driver . ':host=' . self::$host . ';dbname=' . self::$db;
    }

    public static function config(array $config)
    {
        self::$db       = $config['db'];
        self::$driver   = $config['driver'];
        self::$host     = $config['host'];
        self::$password = $config['password'];
        self::$port     = $config['port'];
        self::$userName = $config['userName'];
    }

    public static function connect(array $options = [])
    {
        self::$conn = new \PDO(self::getConnectionString(), self::$userName, self::$password, $options);
    }
}
