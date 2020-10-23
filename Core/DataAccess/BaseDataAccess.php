<?php

namespace Simple\Core\DataAccess;

abstract class BaseDataAccess implements IDataAccess
{
  protected \PDO $conn;

  public function __construct(IDBConfig $dBConfig)
  {
    $this->connect($dBConfig::getConnectionString(), $dBConfig::getUserName(), $dBConfig::getPassword(), $dBConfig::getOptinos());
  }

  protected function connect(string $dsn, string $userName, string $password, array $options = [])
  {
    $this->conn = new \PDO($dsn, $userName, $password, $options);
  }

  public abstract function get(\Simple\Core\DataAccess\Query $query);
  public abstract function getAll(\Simple\Core\DataAccess\Query $query);
  public abstract function run(\Simple\Core\DataAccess\Query $query);
}
