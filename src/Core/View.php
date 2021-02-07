<?php

namespace Simple\Core;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class View
{
    /**
     * @var \Twig\Loader\FilesystemLoader
     */
    private static FilesystemLoader $loader;

    /**
     * @var \Twig\Environment
     */
    private static Environment $twig;

    /**
     * @var array $context
     * 
     * default context
     */
    private static array $context = [];

    public static function init(string $viewsPath, string $compilePath, bool $viewsAutoReload = false)
    {
        self::$loader = new \Twig\Loader\FilesystemLoader($viewsPath);
        self::$twig = new \Twig\Environment(self::$loader, ['cache' => $compilePath, 'auto_reload' => $viewsAutoReload]);
        self::$twig->addExtension(new \Twig\Extension\StringLoaderExtension());
    }

    public static function addToContext(string $name, $value)
    {
        self::$context[$name] = $value;
    }

    public static function render(string $template, array $context = [])
    {
        if ($context !== null) {
            self::$context = array_merge(self::$context, $context);
        }
        echo self::$twig->render($template, self::$context);
    }

    public static function load(string $template, array $context = [])
    {
        if ($context !== null) {
            self::$context = array_merge(self::$context, $context);
        }
        return self::$twig->render($template, self::$context);
    }
}
