<?php

namespace App\Controllers;

use Exceptions\FileNotFoundException;
use Core\App;
use Core\Request;

class BaseController
{
    protected $app;

    public function app()
    {
        return App::getInstance();
    }

    public function request()
    {
        return Request::getInstance();
    }

    public function redirect($url, $permanent = false)
    {
        if ($permanent) {
            header('HTTP/1.1 301 Moved Permanently');
        }
        header('Location: ' . $url);
        exit();
    }

    public function view($view){
        $view = __DIR__ .  '/../../../public/views/' . $view;
        if(!file_exists($view)){
            throw new FileNotFoundException($view);
        }
        return file_get_contents($view);
    }
}