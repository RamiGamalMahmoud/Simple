<?php

namespace Simple\Core\DataAccess;


interface IDataAccess
{
    static function config(array $config);
    function get(\Simple\Core\DataAccess\Query $query);
    function getAll(\Simple\Core\DataAccess\Query $query);
    function run(\Simple\Core\DataAccess\Query $query);
    static function connect(array $options = []);
}
