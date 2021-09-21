<?php

namespace Simple\Core;

class Configrations
{
    private array $configrations;

    public function __construct(string $configDirectory)
    {
        $this->loadConfigrations($configDirectory);
    }

    private function getConfigFiles(string $configDirectory): array
    {
        $configFiles = [];
        $handl = opendir($configDirectory);
        while ($entry = readdir($handl)) {
            if ($entry !== '.' && $entry !== '..') {
                $fileName = $configDirectory . DIRECTORY_SEPARATOR . $entry;
                if (is_file($fileName)) {
                    $configName = explode('.', $entry)[0];
                    $configFiles[$configName] = $fileName;
                }
            }
        }
        closedir($handl);
        return $configFiles;
    }

    private function loadConfigrations(string $configDirectory)
    {
        $configFiles = $this->getConfigFiles($configDirectory);
        foreach ($configFiles as $name => $file) {
            $this->configrations[$name] = require_once($file);
        }
    }

    private function getConfigArray(string $key): array
    {
        if (isset($this->configrations[$key])) {
            return $this->configrations[$key];
        }
        return [];
    }

    public function get(string $key)
    {
        $keys = explode('.', $key);
        $configArray = $this->getConfigArray(array_shift($keys));
        $value = $configArray;

        while ($_key = array_shift($keys)) {
            if (isset($value[$_key])) {
                $value = $value[$_key];
            } else return null;
        }

        return $value;
    }
}
