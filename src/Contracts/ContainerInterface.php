<?php

namespace Simple\Contracts;

interface ContainerInterface
{
    public function get(string $abstract);
    public function has(string $abstract);
}
