<?php

namespace Simple\Core;

/**
 * Class Route to add the routes to the routing table
 */
class Route
{
    /**
     * @var array $routes that will hold the routes table
     * @access private
     */
    private static array $_routes = [];

    private static function checkMiddleware($middleware)
    {
        return $middleware === null ? [] : $middleware;
    }

    /**
     * Add the route to routes table
     * @param string $method: The http method
     * @param string $path: the path that will be added to the routes table
     * @param mixed $action: the action that will be assigned to the route in the routes table
     * @param mixed $middlewars: the middlewares that will be assigned to the route in the routes table
     * @return void
     * @access private
     */
    private static function add(string $method, string $path, $action,  $middleWares)
    {
        self::$_routes[$method][$path] = ['route' => $action, 'middlewares' => self::checkMiddleware($middleWares)];
    }

    /**
     * Add the route to the http GET routes table
     * @param string $path: the path that will be added to the routes table
     * @param mixed $action: the action that will be assigned to the route in the routes table
     * @return void
     * @access public
     */
    public static function get(string $path, $action, $middleWares = null)
    {
        self::add('get', $path, $action, self::checkMiddleware($middleWares));
    }

    /**
     * Add the route to the http POST routes table
     * @param string $path: the path that will be added to the routes table
     * @param mixed $action: the action that will be assigned to the route in the routes table
     * @return void
     * @access public
     */
    public static function post(string $path, $action, $middleWares = null)
    {
        self::add('post', $path, $action, self::checkMiddleware($middleWares));
    }

    /**
     * Adding middlewares path to the routing table
     * @param string $middleware: the name of middle ware | example: checkAuth
     * @param string $route: the route that woll be asigned to the middleware name | example: Namespace\\MiddlewareClassName@method
     * @return void
     */
    public static function middleware(string $middleware, array $route)
    {
        self::$_routes['middlewares'][$middleware] = $route;
    }

    /**
     * Return the routes table for the specified http method: Route::routes('get')
     * @param string $method: the http method name
     * @return array the routes table for http method
     */
    public static function routes($method)
    {
        return isset(self::$_routes[$method]) ? self::$_routes[$method] : false;
    }

    /**
     * Get the all of routes table 
     * @param void
     * @return array the routes table
     */
    public static function getRoutes()
    {
        return self::$_routes;
    }
}
