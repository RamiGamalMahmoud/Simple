<?php

namespace Simple\Core;

use Simple\EXceptions\RouterException;

class Router
{
    private array $routes = [];

    private $path;

    private $requestMethod;

    private string $requestType;

    private array $routeVariables;

    private static string $routesDirectory;

    public static function setRoutesDirectory(string $routesDirectory)
    {
        self::$routesDirectory = $routesDirectory;
    }

    public function __construct(
        string $requestMethod,
        string $requestType,
        string $path
    ) {
        $this->path = $path;
        $this->requestMethod = $requestMethod;
        $this->requestType = $requestType;
        $this->loadRoutesFile(self::$routesDirectory);
        $this->routes = Route::getRoutes();
    }

    public function getRoutes()
    {
        return $this->routes;
    }

    public function loadRoutesFile(string $routesDirectory)
    {
        include $routesDirectory . DIRECTORY_SEPARATOR . $this->requestType . '.php';
    }

    private function pathToRegex($path)
    {
        // Convert the numbers to regulat expression
        $path = preg_replace('/\/\d*\//', '/\d*/', $path);

        // Convert the path to a regular expression: escape forward slashes
        $path = preg_replace('/\//', '\\/', $path);

        // Convert variables e.g. {controller}
        $path = preg_replace('/\{([\w]+)\}/', '(?P<\1>[\w]+)', $path);

        // Convert variables with custom regular expressions e.g. {id:\d+}
        $path = preg_replace('/\{([a-z]+):([^\}]+)\}/', '(?P<\1>\2)', $path);

        // Add start and end delimiters, and case insensitive flag
        $path = '/^' . $path . '$/';

        return $path;
    }

    /**
     * Exatract variables from the $route 
     * 
     * @param string $route
     * @param string $path
     * @return array the extracted variables
     */
    private function extractRouteVariables(string $route, string $path)
    {
        $routeParts = explode('/', $route);
        $pathParts = explode('/', $path);
        if (count($routeParts) === count($pathParts)) {
            $allParts = array_combine($routeParts, $pathParts);
            $variables = [];
            foreach ($allParts as $key => $value) {
                if (preg_match('/\{\w*\}/', $key)) {
                    $key = trim($key, '{');
                    $key = trim($key, '}');
                    $variables[$key] = $value;
                }
            }
            return $variables;
        }
        return [];
    }

    public function resolve($path = '', $method = '')
    {
        $_path = $path;
        $_method = $method;
        $_path = empty($_path) ? $this->path : $_path;
        $_method = empty($_method) ? $this->requestMethod : $_method;

        foreach ($this->routes[$_method] as $key => $value) {
            $regPath = $this->pathToRegex($key);
            if (preg_match($regPath, $_path)) {
                $this->routeVariables = $this->extractRouteVariables($key, $_path);
                return $value;
            }
        }
        throw new RouterException();
    }

    public function get(string $key)
    {
        if (in_array($key, array_keys($this->routeVariables)))
            return $this->routeVariables[$key];
        return 0;
    }
}
