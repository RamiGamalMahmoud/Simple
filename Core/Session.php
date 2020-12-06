<?php

namespace Simple\Core;

/**
 * class Session
 * managing the sessions work
 * @author rami gamal <rami.gamal.mahmoud@gmail.com>
 */
class Session
{

  /**
   * function start: start the session if not exists
   * @return void
   */
  public static function start()
  {
    if ((session_id() == '')) {
      session_start();
    }
  }

  /**
   * func exists: check if the key is exists
   * @param string $key
   * @return boolean
   */
  public static function exists($key)
  {
    return isset($_SESSION[$key]);
  }

  /**
   * func get: return session key if is exists
   * @param string $key
   * @return string|bool
   */
  public static function get($key)
  {
    if (self::exists($key)) {
      return $_SESSION[$key];
    }

    return false;
  }

  /**
   * func set: setting $key value in session
   * @param string $key
   * @param string $value
   * @return void
   */
  public static function set($key, $value)
  {
    $_SESSION[$key] = $value;
  }

  /**
   * func deleteKey: unset the $key from session
   * @param string $key
   * @return void
   */
  public static function deleteKey($key)
  {
    if (self::exists($key)) {
      $_SESSION[$key] = '';
      unset($_SESSION[$key]);
    }
  }

  /**
   * func destroy: destroy the session
   * @return void
   */
  public static function destroy()
  {
    self::start();
    session_unset();
    session_destroy();
  }
}
