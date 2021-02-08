<?php

namespace Simple\Core;

use Simple\Core\Router;

use Simple\Core\Request;
use Simple\Core\IErrorHandler;
use Simple\EXceptions\AuthorizationException;
use Simple\EXceptions\ControllerNotFoundException;
use Simple\EXceptions\MethodNotFoundException;
use Simple\EXceptions\RouterException;

class Application
{
    private static Request $currentRequest;

    private static Router $currentRouter;

    private static IErrorHandler $errorHandler;

    public static function init(IErrorHandler $errorHandler)
    {
        self::$errorHandler = $errorHandler;
    }

    public static function run(bool $catchExceptions = true)
    {
        if (!$catchExceptions) {
            self::start();
        } else {
            try {
                self::start();
            } catch (RouterException $exception) {
                self::$errorHandler->pageNotFound();
            } catch (AuthorizationException $exception) {
                self::$errorHandler->authorizationError();
            } catch (ControllerNotFoundException $exception) {
                self::$errorHandler->pageNotFound();
            } catch (MethodNotFoundException $exception) {
                self::$errorHandler->pageNotFound();
            }
        }
    }

    public static function start(string $path = null, array $params = null)
    {
        $request = new Request($path);
        $router = new Router(
            $request->getRequestMethod(),
            $request->getRequestType(),
            $request->getPath()
        );

        self::$currentRequest = $request;
        self::$currentRouter = $router;

        $route = $router->resolve();

        $routePath = $route['route'];
        $middlewares = $route['middlewares'];

        self::runMiddleWares($middlewares, $router, $request);
        return Dispatcher::dispatche($routePath, $request, $router, $params);
    }

    public static function getCurrentRequest(): Request
    {
        return self::$currentRequest;
    }

    public static function getCurrentRouter(): Router
    {
        return self::$currentRouter;
    }

    private static function runMiddleWares(?array $middlewares, Router $router, Request $request)
    {
        if ($middlewares === null) return;

        foreach ($middlewares as $middleware) {
            Dispatcher::dispatche($router->resolve($middleware, 'middlewares'), $request);
        }
    }
}
