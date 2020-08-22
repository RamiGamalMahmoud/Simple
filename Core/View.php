<?php

namespace Simple\Core;

class View
{
    public static function render(string $template, array $data = [])
    {
        $loader = new \Twig\Loader\FilesystemLoader(VIEWS_PATH);
        $twig = new \Twig\Environment($loader, ['cache' => COMPILE_PATH, 'auto_reload' => VIEWS_AUTO_RELOAD]);
        echo $twig->render($template, $data);
    }
}
