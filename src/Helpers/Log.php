<?php

namespace Simple\Helpers;

class Log
{
    private static string $style = '<style>
    .exported{
        direction: ltr;
        font-weight: bold;
        font-size: 1.2em;
        line-height: 2em;
        box-shadow: 0 0 5px rgba(0, 0, 0, .4);
        border-radius: 5px;
        direction: ltr;
        background-color: #EEE;
        padding: 20px;
        margin: 10px;
        box-sizing: border-box;
        word-wrap: break-word;
        overflow: auto;
    }
    </style>';

    private static function getDubpedText($object)
    {
        $text = self::$style;
        $text .= '<div class="exported container">';
        $text .= '<pre>';
        $text .= var_export($object, true);
        $text .= '</pre>';
        $text .= '</div>';
        return $text;
    }

    public static function dump($object)
    {
        echo self::getDubpedText($object);
        exit;
    }

    public static function print($object)
    {
        echo self::getDubpedText($object);
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
