<?php

namespace Simple\EXceptions;

use Exception;

class MiddleWareException extends Exception
{
    public function __toString()
    {
        return 'MIDDLEWARE FAILED';
    }
}
