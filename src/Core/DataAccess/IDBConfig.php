<?php

namespace Simple\Core\DataAccess;

interface IDBConfig
{
    function getConnectionString(): string;
    function getUserName(): string;
    function getPassword(): string;
    function getPort(): int;
    function getOptinos(): array;
}
