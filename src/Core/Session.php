<?php

namespace Simple\Core;

/**
 * Class Session
 * 
 * Managing the sessions work
 * @author rami gamal <rami.gamal.mahmoud@gmail.com>
 */
class Session
{

    /**
     * Start session
     * 
     * @return void
     */
    public static function start()
    {
        if ((session_id() == '')) {
            session_start();
        }
    }

    /**
     * Checks if the key is has
     * 
     * @param string $key
     * @return boolean
     */
    public static function has($key)
    {
        self::start();
        return isset($_SESSION[$key]);
    }

    /**
     * Get the value assigned for a key
     * 
     * @param string $key
     * @return string the existed value or empty string
     */
    public static function get($key)
    {
        self::start();
        if (self::has($key)) {
            return $_SESSION[$key];
        }

        return '';
    }

    /**
     * Assign a value for a key
     * 
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public static function set($key, $value)
    {
        self::start();
        $_SESSION[$key] = $value;
    }

    /**
     * Delete an assigned value for a key
     * 
     * @param string $key
     * @return void
     */
    public static function deleteKey($key)
    {
        self::start();
        if (self::has($key)) {
            $_SESSION[$key] = '';
            unset($_SESSION[$key]);
        }
    }

    /**
     * Destroy the session
     * 
     * @return void
     */
    public static function destroy()
    {
        self::start();
        session_unset();
        session_destroy();
    }
}
