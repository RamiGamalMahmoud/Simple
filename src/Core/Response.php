<?php

namespace Simple\Core;


class Response
{
    /**
     * Create a json response
     * 
     * @param array data
     * @param int status
     * @return string the encoded json string
     */
    public static function json($data, int $status)
    {
        $json = json_encode($data);
        header('Content-Type: application/json');
        http_response_code($status);
        echo $json;
    }
}
