<?php

namespace Simple\Core;


interface MiddleWareInterFace
{
    public function onFail(callable $callable);
}
