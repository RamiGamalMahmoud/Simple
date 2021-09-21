<?php

namespace Simple\Contracts;

use Closure;

interface MiddleWareInterFace
{
    public function onFail(Closure $callable);
}
