<?php

namespace Simple\Core;

class Redirect
{
  public static function to(string $location)
  {
    header('location: ' . $location);
  }
}
