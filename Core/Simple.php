<?php

namespace Simple\Core;


class Simple
{
  private Router $router;
  private Request $request;

  public function __construct(string $configDir)
  {
    $this->request = new Request();
    $this->router = new Router($this->request, $configDir);
  }

  public function run()
  {
    $route = $this->router->route();
    if ($route !== false) {
      $routePath = $route['route'];
      $middlewares = $route['middlewares'];
      if ($middlewares !== null) {
        if ($this->runMiddleWares($middlewares)) {
          Dispatcher::dispatche($routePath, $this->request);
        } else {
          header('location: /login');
        }
      } else {
        Dispatcher::dispatche($routePath, $this->request);
      }
    } else {
      header('location: /error');
    }
  }

  private function runMiddleWares(array $middlewares)
  {
    foreach ($middlewares as $middleware) {

      if (!Dispatcher::dispatche($this->router->route($middleware, 'middlewares'), $this->request)) {
        return false;
      }
    }

    return true;
  }
}
