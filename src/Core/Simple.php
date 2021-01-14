<?php

namespace Simple\Core;

use Exception;
use Simple\Exceptions\RoutingException;

class Simple
{
    private static Router $router;
    private static Request $request;
    private static string $configDir;

    public static function init(string $configDir)
    {
        self::$configDir = $configDir;
        self::$request = new Request();
        self::$router = new Router(self::$request, $configDir);
    }

    public static function run()
    {
        $route = self::$router->route();
        if ($route) {
            $routePath = $route['route'];
            $middlewares = $route['middlewares'];
            if ($middlewares !== null) {
                if (self::runMiddleWares($middlewares)) {
                    Dispatcher::dispatche($routePath, self::$request);
                } else {
                    throw new Exception('Middle Wares Failed');
                }
            } else {
                Dispatcher::dispatche($routePath, self::$request);
            }
        } else {
            throw new RoutingException('Route Not Found');
        }
    }

    public static function reRun(string $path = '', array $params = null)
    {
        $request = new Request($path);
        $router = new Router($request, self::$configDir);
        $route = $router->route();

        if ($route) {
            $routePath = $route['route'];
            $middlewares = $route['middlewares'];
            if ($middlewares !== null) {
                if (self::runMiddleWares($middlewares)) {
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

    private static function runMiddleWares(array $middlewares)
    {
        foreach ($middlewares as $middleware) {

            if (!Dispatcher::dispatche(self::$router->route($middleware, 'middlewares'), self::$request)) {
                return false;
            }
        }

        return true;
    }
}
