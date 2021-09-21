<?php

use Simple\Core\Application;
use Simple\Core\Request;
use Simple\Core\Router;
use Simple\Helpers\Log;

if (!function_exists('config')) {

    function config(string $key)
    {
        return app()->configrations()->get($key);
    }
}

if (!function_exists('app')) {

    function app(): Application
    {
        return Application::getInstance();
    }
}

if (!function_exists('router')) {

    function router(): Router
    {
        return app()->getCurrentRouter();
    }
}

if (!function_exists('request')) {

    function request(): Request
    {
        return app()->getCurrentRequest();
    }
}

if (!function_exists('dump')) {

    function dump($object)
    {
        Log::dump($object);
    }
}

if (!function_exists('out')) {

    function out($object)
    {
        Log::print($object);
    }
}
