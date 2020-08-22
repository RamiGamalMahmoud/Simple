<?php

namespace Simple\Core;

use Exception;

include WEB_ROUTES;

class Router
{
    private array $routes = [];
    private $path;
    private $method;

    public function __construct(string $path, string $method)
    {
        $this->path = $path;
        $this->method = $method;
        $this->routes = Route::getRoutes();
    }

    public function getRoutes()
    {
        return $this->routes;
    }

    private function pathToRegex($path)
    {
        // Convert the numbers to regulat expression
        $path = preg_replace('/\/\d*\//', '/\d*/', $path);

        // Convert the path to a regular expression: escape forward slashes
        $path = preg_replace('/\//', '\\/', $path);

        // Convert variables e.g. {controller}
        $path = preg_replace('/\{([a-z]+)\}/', '(?P<\1>[a-z-]+)', $path);

        // Convert variables with custom regular expressions e.g. {id:\d+}
        $path = preg_replace('/\{([a-z]+):([^\}]+)\}/', '(?P<\1>\2)', $path);

        // Add start and end delimiters, and case insensitive flag
        $path = '/^' . $path . '$/';

        return $path;
    }

    public function route($path = '', $method = '')
    {
        $path = empty($path) ? $this->path : $path;
        $method = empty($method) ? $this->method : $method;

        foreach ($this->routes[$method] as $key => $value) {

            $key = $this->pathToRegex($key);

            if (preg_match($key, $path)) {

                return $value;
            }
        }
        return false;
    }
}
