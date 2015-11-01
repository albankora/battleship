<?php

namespace App\Controllers;

use App\Models\Game\GameModelInterface;

class GameController extends BaseController
{
    protected $gameModel;

    public function __construct(GameModelInterface $gameModel)
    {
        $this->gameModel = $gameModel;
    }

    public function index()
    {
        return $this->view('template.html');
    }

    public function gameData()
    {
        return $this->gameModel->getGameData();
    }

    public function newGame()
    {
        return $this->gameModel->newGame();
    }

    public function shot()
    {
        $data = $this->request()->getAll();
        return $this->gameModel->shot($data['shot']);
    }
}