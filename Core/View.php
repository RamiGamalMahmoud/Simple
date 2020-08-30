<?php

namespace Simple\Core;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class View
{
    private static FilesystemLoader $loader;
    private static Environment $twig;

    public static function init()
    {
        self::$loader = new \Twig\Loader\FilesystemLoader(VIEWS_PATH);
        self::$twig = new \Twig\Environment(self::$loader, ['cache' => COMPILE_PATH, 'auto_reload' => VIEWS_AUTO_RELOAD]);
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

View::init();