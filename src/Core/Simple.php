<?php

namespace Simple\Core;

use Exception;
use Simple\Exceptions\RoutingException;

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
            if ($middlewares !== null) {
                if (self::runMiddleWares($middlewares, $router, $request)) {
                    return Dispatcher::dispatche($routePath, $request, $params);
                } else {
                    throw new Exception('Middle Wares Failed');
                }
            } else {
                return Dispatcher::dispatche($routePath, $request, $params);
            }
        } else {
            throw new RoutingException('Route Not Found');
        }
    }

    private static function runMiddleWares(array $middlewares, Router $router, Request $request)
    {
        foreach ($middlewares as $middleware) {

            if (!Dispatcher::dispatche($router->route($middleware, 'middlewares'), $request)) {
                return false;
            }
        }

        return true;
    }
}
