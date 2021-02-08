<?php

namespace Simple\Core;

use Exception;
use Simple\EXceptions\MiddleWareException;
use Simple\Exceptions\RouterException;

class Simple
{
    private static string $configDir;

    public static function init(string $configDir)
    {
        self::$configDir = $configDir;
    }

    public static function run()
    {
        self::resolve();
    }

    public static function resolve(string $path = null, array $params = null)
    {
        $request = new Request($path);
        $router = new Router($request, self::$configDir);
        $route = $router->route();

        if ($route) {
            $routePath = $route['route'];
            $middlewares = $route['middlewares'];
            self::runMiddleWares($middlewares, $router, $request);
            return Dispatcher::dispatche($routePath, $request, $router, $params);
        } else {
            throw new RouterException('Route Not Found');
        }
    }

    private static function runMiddleWares(?array $middlewares, Router $router, Request $request)
    {
        foreach ($middlewares as $middleware) {

            if (!Dispatcher::dispatche($router->route($middleware, 'middlewares'), $request)) {
                throw new MiddleWareException();
            }
        }

        return true;
    }
}
