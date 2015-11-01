<?php
$router = Libs\Router::getInstance();
$router->get('/', 'App\Controllers\GameController@index');
$router->get('newGame', 'App\Controllers\GameController@newGame');
$router->get('gameData', 'App\Controllers\GameController@gameData');
$router->post('shot', 'App\Controllers\GameController@shot');