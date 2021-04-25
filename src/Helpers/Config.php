<?php

namespace Simple\Helpers;

class Config
{
    private static $configDirectory;

    private static array $configFiles = [];

    public static function load($configDirectory)
    {
        self::$configDirectory = $configDirectory;
    }

    public static function get($key)
    {
        $keyParts = explode('.', $key);
        $fileName = self::$configDirectory . DIRECTORY_SEPARATOR  . $keyParts[0] . '.php';
        $key = $keyParts[1];

        if (key_exists($fileName, self::$configFiles)) {
            return self::$configFiles[$fileName][$key];
        }

        if (file_exists($fileName)) {
            $arr = require $fileName;
            self::$configFiles[$fileName] = $arr;
            if (key_exists($key, $arr)) {
                return $arr[$key];
            }
        }
    }
}
