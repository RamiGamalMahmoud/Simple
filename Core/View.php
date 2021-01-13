<?php

namespace Simple\Core;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class View
{
  private static FilesystemLoader $loader;
  private static Environment $twig;

  public static function init(string $viewsPath, string $compilePath, bool $viewsAutoReload = false)
  {
    self::$loader = new \Twig\Loader\FilesystemLoader($viewsPath);
    self::$twig = new \Twig\Environment(self::$loader, ['cache' => $compilePath, 'auto_reload' => $viewsAutoReload]);
    self::$twig->addExtension(new \Twig\Extension\StringLoaderExtension());
  }
  public static function render(string $template, array $context = [])
  {
    echo self::$twig->render($template, $context);
  }

  public static function load(string $template, array $context)
  {
    return self::$twig->render($template, $context);
  }
}
