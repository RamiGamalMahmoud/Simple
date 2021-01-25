<?php

namespace Simple\Core;


/**
 * Dispatching a route
 * 
 * @author rami-gamal <rami.gamal.mahmoud@gmail.com>
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
        if ($route == null) {
            throw new \Simple\EXceptions\RouterException('RouteExecption: Route not existed');
            exit;
        }

        if (is_array($route) && count($route) === 2) {
            $controller = $route[0];
            $method = $route[1];
            $obj = new $controller($request, $params);
            return call_user_func([$obj, $method], $request);
        } elseif (is_callable($route)) {
            return $route($request);
        } else {
            throw new \Simple\EXceptions\RouterException('RouteExecption: Route not existed');
        }
    }
}
