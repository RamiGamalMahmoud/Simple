<?php

namespace Simple\Core\DataAccess;


interface IDataAccess
{
    function __construct(IDBConfig $dBConfig);

    function get(\Simple\Core\DataAccess\Query $query);
    function getAll(\Simple\Core\DataAccess\Query $query);
    function run(\Simple\Core\DataAccess\Query $query);
}
