<?php

namespace Simple\Core;


class Dispatcher
{

    public static function dispatche($route, IRequest $request, $params = null)
    {
        if ($route == null) {
            throw new \Exception('RouteExecption: Route not existed');
            exit;
        }

        if (is_array($route)) {
            $_route = $route['route'];
        } else if (is_string($route)) {
            $_route = $route;
        }
        $dispatched = explode('@', $_route);
        $controller = $dispatched[0];
        $action     = $dispatched[1];
        $obj        = new $controller($request, $params);
        return call_user_func([$obj, $action], $request);
    }
}
