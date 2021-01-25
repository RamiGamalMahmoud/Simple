<?php

namespace Simple\EXceptions;

use Exception;

class RouterException extends Exception
{
    public function __toString()
    {
        return 'Route Not Found';
    }
}
