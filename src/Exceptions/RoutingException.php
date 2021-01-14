<?php

namespace Simple\EXceptions;

use Exception;

class RoutingException extends Exception
{
    public function __toString()
    {
        return 'Route Not Found';
    }
}
