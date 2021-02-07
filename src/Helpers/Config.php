<?php

namespace Simple\Helpers;

class Config
{
    private static $configDirectory;

    private static array $loadedConfigFiles = [];

    public static function load($configDirectory)
    {
        self::$configDirectory = $configDirectory;
    }

    public static function get($name)
    {
        $parts = explode('.', $name);
        $fileName = self::$configDirectory . DIRECTORY_SEPARATOR  . $parts[0] . '.php';
        $key = $parts[1];

        if (key_exists($fileName, self::$loadedConfigFiles)) {
            return self::$loadedConfigFiles[$fileName][$key];
        }

        if (file_exists($fileName)) {
            $arr = require $fileName;
            self::$loadedConfigFiles[$fileName] = $arr;
            if (key_exists($key, $arr)) {
                return $arr[$key];
            }
        }
    }
}
