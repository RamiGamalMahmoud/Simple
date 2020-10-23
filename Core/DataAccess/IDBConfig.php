<?php

namespace Simple\Core\DataAccess;

interface IDBConfig
{
  static function getConnectionString(): string;
  static function getUserName(): string;
  static function getPassword(): string;
  static function getPort(): int;
  static function getOptinos(): array;
}
