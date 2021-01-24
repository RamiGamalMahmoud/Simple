<?php

namespace Simple\Core\DataAccess;

use PDO;

class MySQLAccess extends BaseDataAccess
{
    public function get(\Simple\Core\DataAccess\Query $query)
    {
        return $this->prepare(
            $query->getQueryString(),
            $query->getQueryParams()
        )->fetch(PDO::FETCH_ASSOC);
    }

    public function getAll(\Simple\Core\DataAccess\Query $query)
    {
        return $this->prepare(
            $query->getQueryString(),
            $query->getQueryParams()
        )->fetchAll(PDO::FETCH_ASSOC);
    }

    public function run(\Simple\Core\DataAccess\Query $query)
    {
        return $this->prepare(
            $query->getQueryString(),
            $query->getQueryParams()
        )->rowCount();
    }

    private function prepare(string $sql, array $params = null)
    {
        $stmnt = self::$conn->prepare($sql);
        $stmnt->execute($params);
        return $stmnt;
    }
}
