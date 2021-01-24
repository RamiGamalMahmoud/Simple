<?php

namespace Simple\Core;

/**
 * Make Redirect
 * 
 * @author rami-gamal <rami.gamal.mahmoud@gmail.com>
 */
class Redirect
{
    /**
     * Redirect to a location
     * 
     * @param string $location the new location to redirect
     * @return void
     */
    public static function to(string $location)
    {
        header('location: ' . $location);
    }
}
