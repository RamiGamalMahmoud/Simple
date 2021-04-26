<?php

namespace Simple\Core\Container;

use Closure;
use ReflectionClass;
use Simple\Contracts\ContainerInterface;

class Container implements ContainerInterface
{
    private array $services = [];

    private function createDependency($abstract)
    {
        $service = $this->get($abstract);
        if (is_a($service, Closure::class)) {
            $service = $service();
        }
        return $service;
    }

    private function getDependencies(ReflectionClass $reflector): array
    {
        $dependencies = [];
        $constructor = $reflector->getConstructor();
        if ($constructor !== null) {
            $params = $constructor->getParameters();
            $dependencies = array_map(function ($param) {
                return $this->createDependency($param->getType()->getName());
            }, $params);
        }

        return $dependencies;
    }

    public function bind(string $abstract, $concrete = null)
    {
        if ($concrete === null) {
            $this->services[$abstract] = $abstract;
        }
        $this->services[$abstract] = $concrete;
    }

    public function resolve(string $className)
    {
        $reflection = new ReflectionClass($className);
        $dependencies = $this->getDependencies($reflection);

        // dump($dependencies);
        return $reflection->newInstanceArgs($dependencies);
    }

    public function get(string $abstract)
    {
        if ($this->has($abstract)) {
            return $this->services[$abstract];
        }
        return 'Not Existed';
    }

    public function has(string $name): bool
    {
        return isset($this->services[$name]);
    }
}
