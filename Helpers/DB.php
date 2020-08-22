<?php

namespace Simple\Helpers;

use Exception;
use \PDO;

if(!defined('DB_CONFIG')) define('DB_CONFIG', '../config/database.php');
class DB
{
    /**
     * @var \PDO $db \PDO connection object
     */
    private ?\PDO $db;

    /**
     * @var string $query The query statement
     */
    private string $query = '';

    /**
     * @var array $queryParams holds the query parameters
     */
    private array $queryParams = [];

    /**
     * Construct the object and creating the connection
     */
    public function __construct()
    {
        $dbConfig = require DB_CONFIG;
        $driver   = $dbConfig['driver'];
        $host     = $dbConfig['host'];
        $db       = $dbConfig['dbName'];
        $userName = $dbConfig['userName'];
        $password = $dbConfig['password'];

        $this->db = new PDO($driver . ':host=' . $host . ';dbname=' . $db, $userName, $password, [PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8']);
    }

    /**
     * Return fields in a database table
     * @param stirng $table the table name
     * @return array
     */
    public function fetchColumnsInTable(string $table)
    {
        $this->query = 'SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.columns WHERE TABLE_NAME = "' . $table . '"';
        $columns = $this->fetch();
        $reault = [];
        foreach ($columns as $column) {
            foreach ($column as $key => $value) {
                $result[] = $value;
            }
        }

        return $result;
    }

    /**
     * Return the query statement
     * @return string The query statement
     */
    public function getQuery()
    {
        $this->query = $this->query . ';';
        return $this->query;
    }

    /**
     * Get $queryParams holds the query parameters
     * 
     * @param void
     * @return  array
     */
    public function getQueryParams()
    {
        return $this->queryParams;
    }

    /**
     * Execute the query statement and return the data as associtive array with fetchAll
     * @param void
     * @return array $data
     */
    public function fetch()
    {
        $stmt = $this->execute();

        if ($stmt->rowCount()) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        return false;
    }

    /**
     * Execute the query statement and return the first row of data as associtive array with fetch
     * @param void
     * @return array|bool
     */
    public function fetchFirst()
    {
        $stmt = $this->execute();

        if ($stmt->rowCount()) {
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
        return false;
    }

    /**
     * Crate and INSERT INTO statement
     * @param string $table
     * @param array $fields
     * @return SM\Core\Database\DB
     */
    public function insertInto(string $table, array $fields)
    {
        $this->query = 'INSERT INTO ' . $table . ' ( ';
        $this->queryParams = [];

        foreach ($fields as $field) {
            $this->query .= $field . ', ';
        }

        $this->query = trim($this->query, ', ') . ' ) ';
        return $this;
    }

    /**
     * Creating query statement with SELECT clause
     * @param array $fields The database table fields to be extracted
     * @return SM\Core\Database\DB
     */
    public function select(array $fields)
    {
        $this->query = 'SELECT ';
        $count = count($fields);
        for ($i = 0; $i < $count; $i++) {
            $this->query .= $fields[$i] . ', ';
        }
        $this->query = trim($this->query, ', ') . ' ';
        return $this;
    }

    /**
     * Creating query statement with SELECT clause and the '*'
     * @param void
     * @return SM\Core\Database\DB
     */
    public function selectAll()
    {
        $this->query = 'SELECT * ';
        return $this;
    }

    /**
     * Crate a JOIN statement
     * @param string $table the table name
     * @return SM\Core\Database\DB
     */
    public function join(string $table)
    {
        return $this->makeJoin('JOIN', $table);
    }

    /**
     * Crate a RIGHT JOIN statement
     * @param string $table the table name
     * @return SM\Core\Database\DB
     */
    public function rightJoin(string $table)
    {
        return $this->makeJoin('RIGHT JOIN', $table);
    }

    /**
     * Crate an UPDATE statement
     * @param string $table
     * @return SM\Core\Database\DB
     */
    public function update(string $table)
    {
        $this->query = 'UPDATE ' . $table . ' ';
        $this->queryParams = [];
        return $this;
    }

    /**
     * Adding FROM to the query statement
     * @param string $table Table name
     * @return SM\Core\Database\DB
     */
    public function from(string $table)
    {
        $this->query .= 'FROM ' . $table . ' ';
        return $this;
    }

    /**
     * Adding WHERE clause to the query statement
     * @param string $field Field name
     * @param string $operator The comparison operator will be used [<|<=|=|>=|>|<>]
     * @param string $value The vialue to compare with
     * @return SM\Core\Database\DB
     */
    public function where(string $field, string $operator, string $value)
    {
        $this->query .= ' WHERE ' . $field . ' ' . $operator . ' ? ';
        $this->queryParams[] = $value;
        return $this;
    }

    /**
     * Appending the logical operator OR to the query statement
     * @param string $field Field name
     * @param string $operator The comparison operator will be used [<|<=|=|>=|>|<>]
     * @param string $value The vialue to compare with
     * @return SM\Core\Database\DB
     */
    public function andWhere(string $field, string $operator, string $value)
    {
        return $this->addLogicalCluase('AND', $field, $operator, $value);
    }

    /**
     * Appending the logical operator AND to the query statement
     * @param string $field Field name
     * @param string $operator The comparison operator will be used [<|<=|=|>=|>|<>]
     * @param string $value The vialue to compare with
     * @return SM\Core\Database\DB
     */
    public function orWhere(string $field, string $operator, string $value)
    {
        return $this->addLogicalCluase('OR', $field, $operator, $value);
    }

    /**
     * Crate a LEFT JOIN statement
     * @param string $table the table name
     * @return SM\Core\Database\DB
     */
    public function leftJoin(string $table)
    {
        return $this->makeJoin('LEFT JOIN', $table);
    }

    /**
     * Appending the ON clause to the query statement
     * @param string $leftField
     * @param string $rightField
     * @return SM\Core\Database\DB
     */
    public function on(string $leftField, string $rightField)
    {
        $this->query .= 'ON' . ' ' . $leftField . ' = ' . $rightField . ' ';
        return $this;
    }

    /**
     * Appending the LIMIT clause to the query statement
     * @param int $limit
     * @return SM\Core\Database\DB
     */
    public function limit(int $limit)
    {
        $this->query .= 'LIMIT ' . $limit . ' ';
        return $this;
    }

    /**
     * Create select count statement
     * @param string $field Field name to count on
     * @return SM\Core\Database\DB
     */
    public function count(string $field)
    {
        $this->query = 'SELECT count(' . $field . ') AS count ';
        return $this;
    }

    /**
     * Appending the SET clause to the query statement
     * @param array $fields
     * @param array $values
     * @return SM\Core\Database\DB
     */
    public function set(array $fields, array $values)
    {
        $fieldsCount = count($fields);
        $valuesCount = count($values);

        if ($fieldsCount !== $valuesCount) {
            throw new Exception('Number of fieldes should be same as number of values');
        }

        $this->query .= 'SET ';
        for ($i = 0; $i < $fieldsCount; $i++) {
            $this->query .= $fields[$i] . '=?' . ', ';
            $this->queryParams[] = $values[$i];
        }

        $this->query = trim($this->query, ', ') . ' ';
        return $this;
    }

    /**
     * Append the VALUES (value-1, value-2, ..., value-n) to the INSERT INTO statement
     * @param array $values
     * @return SM\Core\Database\DB
     */
    public function values(array $values)
    {
        $this->query .= 'VALUES ( ';

        $items = count($values);
        for ($i = 0; $i < $items; $i++) {
            $this->query .= '?, ';
            $this->queryParams[] = $values[$i];
        }
        $this->query = trim($this->query, ', ') . ' ) ';
        return $this;
    }

    /**
     * Add logical operatores to the query statement [AND|OR]
     * @param string $logic the logical operator to be added [AND|OR]
     * @param string $field The field name to test
     * @param string $operator [<|<=|=|>=|>|<>]
     * @param string $value to compare
     * @return SM\Core\Database\DB
     * @access private
     */
    private function addLogicalCluase(string $logic, string $field, string $operator, string $value)
    {
        $this->query .= $logic . ' ' . $field . ' ' . $operator . ' ? ';
        $this->queryParams[] = $value;
        return $this;
    }

    /**
     * Add JOIN clause to the query statement
     * @param string $type The JOIN type [JOIN|LEFT JOIN|RIGHT JOIN|FULL JOIN|INNER JOIN]
     * @param string $table The joined table
     * @return SM\Core\Database\DB
     * @access private
     */
    private function makeJoin(string $type, string $table)
    {
        $this->query .= $type . ' ' . $table . ' ';
        return $this;
    }

    /**
     * Execute the query statement with the query parameters
     * @param void
     * @return PDOStatement
     * @access private
     */
    private function execute()
    {
        $this->query = $this->query . ';';
        $stmt = $this->db->prepare($this->query);
        $stmt->execute($this->queryParams);

        return $stmt;
    }

    /**
     * Execute none query statement with query parameters
     * @param void
     * @return int the number of affected rows
     */
    public function run()
    {
        $this->query = $this->query . ';';
        $stmt = $this->db->prepare($this->query);
        $stmt->execute($this->queryParams);

        return $stmt->rowCount();
    }
}
