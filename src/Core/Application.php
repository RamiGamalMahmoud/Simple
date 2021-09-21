<?php

namespace Simple\Core;

use Simple\Core\Router;
use Simple\Core\Request;
use Simple\Core\Container\Container;
use Simple\EXceptions\RouterException;
use Simple\Core\DataAccess\MySQLAccess;
use Simple\Contracts\ErrorHandlerInterface;
use Simple\Core\Database\ConnectionFactory;
use Simple\EXceptions\AuthorizationException;
use Simple\EXceptions\MethodNotFoundException;
use Simple\EXceptions\ControllerNotFoundException;

class Application
{
    /**
     * The root directory for the application
     * 
     * @var string $basePath
     */
    private string $basePath;

    private ConnectionFactory $connectionFactory;

    private Configrations $configrations;

    /**
     * An array that holds service providors
     * 
     * @var array $serviceProviders
     */
    private array $serviceProviders;

    /**
     * @var \Simple\Core\Container\Container
     */
    private Container $container;

    /**
     * @var \Simple\Core\Request
     */
    private Request $currentRequest;

    /**
     * @var \Simple\Core\Router
     */
    private Router $currentRouter;

    /**
     * @var \Simple\Contracts\ErrorHandlerInterface
     */
    private ErrorHandlerInterface $errorHandler;

    /**
     * @var \Simple\Core\Application
     */
    private static ?Application $app = null;

    public static function getInstance(string $basePath = ''): Application
    {
        if (!isset(self::$app)) {
            self::$app = new Application($basePath);
        }
        return self::$app;
    }

    private function __construct(string $basePath)
    {
        $this->setBasePath($basePath);
        $this->runScripts();
    }

    public function configrations()
    {
        return $this->configrations;
    }

    private function loadConfigrations()
    {
        $this->configrations = new Configrations($this->basePath . DIRECTORY_SEPARATOR . 'config');
        $this->connectionFactory = new ConnectionFactory(
            config('database.connections'),
            config('database.driver')
        );

        Router::setRoutesDirectory(
            $this->basePath . DIRECTORY_SEPARATOR . 'routes'
        );

        $this->setAppTimeZone(config('app.time_zone'));

        MySQLAccess::config(config('database.connections.' . config('database.driver')));
        MySQLAccess::connect();
        View::init(
            $this->resourcePath(config('twig.templates_path')),
            $this->storagePath(config('twig.compile_path')),
            config('twig.auto_reload')
        );
    }

    private function setAppTimeZone(string $timeZone)
    {
        date_default_timezone_set($timeZone);
    }

    private function setSelfBindings()
    {
        $this->bind(Request::class, $this->getCurrentRequest());
        $this->bind(Router::class, $this->getCurrentRouter());
    }

    private function runScripts()
    {
        require_once(dirname(__DIR__) . DIRECTORY_SEPARATOR . 'helpers.php');
    }

    private function setBasePath(string $basePath)
    {
        $this->basePath = $basePath;
    }

    private function loadServiceProviders($providers)
    {
        $this->serviceProviders = $providers;

        foreach ($this->serviceProviders as $provider) {
            $providerInstance = new $provider;
            $providerInstance->register();
        }
    }

    public function resourcePath(string $path)
    {
        return $this->basePath . DIRECTORY_SEPARATOR . 'resources' . DIRECTORY_SEPARATOR . $path;
    }
    private function runMiddleWares(?array $middlewares, Router $router, Request $request)
    {
        if ($middlewares === null) return;

        foreach ($middlewares as $middleware) {
            Dispatcher::dispatche($router->resolve($middleware, 'middlewares'), $request);
        }
    }

    public function setErrorHandler(ErrorHandlerInterface $errorHandler)
    {
        $this->errorHandler = $errorHandler;
    }
    public function storagePath(string $path)
    {
        return $this->basePath . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . $path;
    }

    public function run(bool $catchExceptions = true)
    {
        if (!$catchExceptions) {
            $this->start();
        } else {
            try {
                $this->start();
            } catch (RouterException $exception) {
                $this->errorHandler->pageNotFound();
            } catch (AuthorizationException $exception) {
                $this->errorHandler->authorizationError();
            } catch (ControllerNotFoundException $exception) {
                $this->errorHandler->pageNotFound();
            } catch (MethodNotFoundException $exception) {
                $this->errorHandler->pageNotFound();
            }
        }
    }

    public function start(string $path = null, array $params = null)
    {
        $this->loadConfigrations();
        $this->container = new Container();
        $this->loadServiceProviders(config('app.providers'));

        $request = new Request($path);
        $router = new Router(
            $request->getRequestMethod(),
            $request->getRequestType(),
            $request->getPath()
        );

        $this->currentRequest = $request;
        $this->currentRouter = $router;

        $this->setSelfBindings();

        $route = $router->resolve();

        $routePath = $route['route'];
        $middlewares = $route['middlewares'];

        $this->runMiddleWares($middlewares, $router, $request);

        return Dispatcher::dispatche($routePath, $request, $router, $params);
    }

    public function getCurrentRequest(): Request
    {
        return $this->currentRequest;
    }

    public function getCurrentRouter(): Router
    {
        return $this->currentRouter;
    }

    public function getContainer(): Container
    {
        return $this->container;
    }

    public function bind(string $abstract, $concrete)
    {
        $this->container->bind($abstract, $concrete);
    }
}
