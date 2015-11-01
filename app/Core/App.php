<?php

namespace Core;

class App
{
    use Singletonable;

    public function run()
    {
        $router = Router::getInstance();
        $response = Response::getInstance();
        $response->send($router->dispatch());
    }

    public function baseUrl()
    {
        if (isset($_SERVER['HTTPS'])) {
            $protocol = ($_SERVER['HTTPS'] && $_SERVER['HTTPS'] != "off") ? "https" : "http";
        } else {
            $protocol = 'http';
        }
        return $protocol . "://" . $_SERVER['HTTP_HOST'];
    }
}