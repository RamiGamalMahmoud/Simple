<?php

namespace Simple\Helpers;

class Convert
{
  public static function convertArray(array $arr)
  {
    $result = [];
    foreach ($arr as $value) {
      $result[] = self::convertNumbers($value);
    }
    return $result;
  }

  public static function convertNumbers($str, $sys = "IND")
  {
    $western = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '.');
    $eastern = array('٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩', ',');

    return $sys == "IND" ?
      str_replace($western, $eastern, $str) :
      str_replace($eastern, $western, $str);
  }
}
