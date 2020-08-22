<?php

namespace Simple\Core;

class Dispatcher
{
    private static $route;
    private static $request;

    public static function dispatche($route, $request = null, $params = null)
    {
        $dispatched = explode('@', $route);
        $controller = $dispatched[0];
        $action = $dispatched[1];
        $obj = new $controller($request, $params);
        return call_user_func([$obj, $action], $request);
    }
}
