<?php

namespace Simple\Helpers;

class Functions
{
    public static function dump($object)
    {
        echo '<style>pre{
            font-weight: bold;
            font-size: 1.5em;
            line-height: 2em;
            color: brown;
            direction: ltr;
            background-color: #EEE;
            padding: 20px;
            width: fit-content;
            box-sizing: border-box;
        }</style>';
        echo '<pre>';
        var_export($object);
        echo '</pre>';
    }

    public static function toCammel($str)
    {
        $result = strtolower($str);
        preg_match_all('/_[a-z]/', $result, $matches);
        foreach ($matches[0] as $match) {
            $c = str_replace('_', '', strtoupper($match));
            $result = str_replace($match, $c, $result);
        }
        return $result;
    }
}
