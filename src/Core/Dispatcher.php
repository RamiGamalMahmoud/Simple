<?php

namespace Simple\Core;

use Exception;

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
     * @param $route
     * @param \Simple\Core\Request
     * @return mixed
     * @throws \Simple\EXceptions\RouterException
     */
    public static function dispatche($route, IRequest $request, $params = null)
    {
        if (is_array($route) && count($route) === 2) {
            $controller = $route[0];
            $method = $route[1];
            if (!class_exists($controller)) {
                throw new \Simple\EXceptions\ControllerNotFoundException();
            } else if (!method_exists($controller, $method)) {
                throw new \Simple\EXceptions\MethodNotFoundException();
            }
            $obj = new $controller($request, $params);
            return call_user_func([$obj, $method], $request);
        } elseif (is_callable($route)) {
            return $route($request);
        } else {
            throw new \Simple\EXceptions\RouterException('RouteExecption: Route not existed');
        }
    }
}
