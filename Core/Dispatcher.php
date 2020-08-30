<?php

namespace Simple\Core;


class Dispatcher
{

    public static function dispatche(string $route, IRequest $request, $params = null)
    {
        $dispatched = explode('@', $route);
        $controller = $dispatched[0];
        $action     = $dispatched[1];
        $obj        = new $controller($request, $params);
        return call_user_func([$obj, $action], $request);
    }
}
