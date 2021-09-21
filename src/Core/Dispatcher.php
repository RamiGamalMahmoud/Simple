<?php

namespace Simple\Core;

use ReflectionMethod;
use Simple\Contracts\RequestInterface;

/**
 * Dispatching a route
 * 
 * @author rami-gamal <rami.gamal.mahmoud@gmail.com>
 * @package Simple
 */
class Dispatcher
{

    /**
     * Call the roue action
     * 
     * @param array $route
     * @param \Simple\Contracts\RequestInterface
     * 
     * @return mixed
     * @throws \Simple\EXceptions\RouterException
     */
    public static function dispatche($route, RequestInterface $request)
    {
        if (is_array($route) && count($route) === 2) {
            $controllerName = $route[0];

            $method = $route[1];

            if (!class_exists($controllerName)) {
                throw new \Simple\EXceptions\ControllerNotFoundException();
            } else if (!method_exists($controllerName, $method)) {
                throw new \Simple\EXceptions\MethodNotFoundException();
            }

            $controller = self::startContainer($controllerName);

            return call_user_func([$controller, $method], $request);
        } elseif (is_callable($route)) {
            return $route($request);
        } else {
            throw new \Simple\EXceptions\RouterException('RouteExecption: Route not existed');
        }
    }

    private static function startContainer(string $controllerName)
    {
        $container = app()->getContainer();
        return $container->resolve($controllerName);
    }
}
