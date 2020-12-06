<?php

namespace Simple\Core;

use Exception;

class Router
{
  private array $routes = [];
  private $path;
  private $method;
  private IRequest $request;

  public function __construct(IRequest $request, string $configDir)
  {
    $this->request = $request;
    $this->path = $this->request->getPath();
    $this->method = $this->request->getRequestMethod();
    include $this->getRoutesFile($configDir);
    $this->routes = Route::getRoutes();
  }

  public function getRoutes()
  {
    return $this->routes;
  }

  public function getRoutesFile(string $configDir)
  {
    $requestType = $this->request->getRequestType();
    return $configDir . DIRECTORY_SEPARATOR . $requestType . '.php';
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
    $_path = $path;
    $_method = $method;
    $_path = empty($_path) ? $this->path : $_path;
    $_method = empty($_method) ? $this->method : $_method;

    foreach ($this->routes[$_method] as $key => $value) {
      $_key = $this->pathToRegex($key);
      if (preg_match($_key, $_path)) {
        return $value;
      }
    }
    return false;
  }
}
