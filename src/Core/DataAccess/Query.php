<?php

namespace Simple\Core\DataAccess;

class Query
{
    // SELECT [columns] FROM [table] 
    // DELETE FROM [table] WHERE condition
    // UPDATE table SET col=value, col-2=value-2, ..... WHERE condition
    // INSERT INTO [table] (columnd) VALUES (values);

    private string $queryString = '';
    private array $queryParams = [];

    public const ASC = 'ASC';
    public const DESC = 'DESC';

    public function getQueryString()
    {
        return $this->queryString;
    }

    public function getQueryParams()
    {
        return $this->queryParams;
    }

    public function select(array $selection)
    {
        $this->queryString = 'SELECT ';
        $this->queryString .= implode(', ', $selection);
        $this->queryString .= ' ';
        return $this;
    }

    public function call($proc, array $params = [])
    {
        $this->queryString = "CALL $proc(";
        if ($params !== null && !empty($params)) {
            $this->queryString .= implode(', ', array_map(function () {
                return '?';
            }, $params)) . ')';
            $this->queryParams = $params;
        }
    }

    public function selectCount(string $column)
    {
        $this->queryString = "SELECT count($column) AS count ";
        return $this;
    }

    public function selectAll()
    {
        $this->queryString = 'SELECT * ';
        return $this;
    }

    public function delete()
    {
        $this->queryString = 'DELETE ';
        return $this;
    }

    public function from(string $tableName)
    {
        $this->queryString .= 'FROM ';
        if (is_string($tableName)) {
            $this->queryString .= "$tableName ";
        } else if (is_a($tableName, self::class)) {
            $this->queryString .= "({$tableName->queryString}) ";
            $this->queryParams = array_merge($this->queryParams, $tableName->queryParams);
        }
        return $this;
    }

    // INSERT INTO $tableName (columns) VALUES (valuea);
    // insert
    public function insertInto(string $tableName)
    {
        $this->queryString = "INSERT INTO $tableName ";
        return $this;
    }

    public function values(array $data)
    {
        $columns = [];
        $values = [];
        foreach ($data as $key => $value) {
            array_push($columns, $key);
            array_push($values, '?');
            array_push($this->queryParams, $value);
        }
        $this->queryString .= '(';
        $this->queryString .= implode(', ', $columns);
        $this->queryString .= ') VALUES (';
        $this->queryString .= implode(', ', $values) . ') ';
        return $this;
    }

    // update
    public function update(string $tableName)
    {
        $this->queryString = "UPDATE $tableName ";
        return $this;
    }

    public function set(array $data)
    {
        $values = [];
        foreach ($data as $key => $value) {
            array_push($values, $key . ' = ?');
            array_push($this->queryParams, $value);
        }
        $this->queryString .= 'SET ';
        $this->queryString .= implode(', ', $values);
        $this->queryString .= ' ';
        return $this;
    }
    // join

    private function makeJoin(string $joinType, string $tableName)
    {
        return "$joinType $tableName ";
    }

    public function join(string $tableName)
    {
        $this->queryString .= $this->makeJoin('JOIN', $tableName);
        return $this;
    }

    public function leftJoin(string $tableName)
    {
        $this->queryString .= $this->makeJoin('LEFT JOIN', $tableName);
        return $this;
    }

    public function righttJoin(string $tableName)
    {
        $this->queryString .= $this->makeJoin('RIGHT JOIN', $tableName);
        return $this;
    }

    // conditions
    public function where(string $column, string $operator, $criteria)
    {
        $this->queryString .= "WHERE $column $operator ? ";
        array_push($this->queryParams, $criteria);
        return $this;
    }

    public function andWhere(string $column, string $operator, $criteria)
    {
        $this->queryString .= "AND $column $operator ? ";
        array_push($this->queryParams, $criteria);
        return $this;
    }

    public function orWhere(string $column, string $operator, $criteria)
    {
        $this->queryString .= "OR $column $operator ? ";
        array_push($this->queryParams, $criteria);
        return $this;
    }

    public function orderBy(array $columns)
    {
        $this->queryString .= 'ORDER BY ';
        foreach ($columns as $key => $value) {
            if (is_string($key)) {
                $this->queryString .= "$key $value , ";
            } else if (is_numeric($key)) {
                $this->queryString .= "$value , ";
            }
        }
        $this->queryString = trim($this->queryString, ', ') . ' ';
        return $this;
    }

    public function on(string $firstColumn, string $otherColumn)
    {
        $this->queryString .= "ON $firstColumn = $otherColumn ";
        return $this;
    }

    public function limit(int $count)
    {
        $this->queryString .= "LIMIT $count";
    }
}
