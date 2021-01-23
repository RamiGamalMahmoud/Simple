<?php

namespace Simple\Core;

use Exception;
use Simple\Helpers\Log;

class Dispatcher
{

    public static function dispatche($route, IRequest $request, $params = null)
    {
        if ($route == null) {
            throw new \Exception('RouteExecption: Route not existed');
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
            throw new Exception('path not existed');
        }
    }
}
