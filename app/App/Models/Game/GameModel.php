<?php

namespace App\Models\Game;

use App\Domain\Logic\GameLogic;
use App\Domain\Repository\Game\GameRepositoryInterface;
use App\Models\AbstractModel;
use Core\Session;

class GameModel extends AbstractModel implements GameModelInterface
{
    protected $gameRepository;
    protected $session;
    protected $gameLogic;
    protected $row = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J'];

    public function __construct(GameLogic $gameLogic, GameRepositoryInterface $gameRepository, Session $session)
    {
        $this->gameLogic = $gameLogic;
        $this->gameRepository = $gameRepository;
        $this->session = $session;
    }

    /**
     * @param $coordinate
     * @return mixed
     */
    public function shot($coordinate)
    {
        $gameId = $this->session->get('game_id');
        if ($gameId == null) {
            return $this->errorResponse();
        }

        $shot = $this->parseShot($coordinate);

        $result = $this->gameRepository->checkShot($gameId, $shot);

        if ($result === false) {
            return $this->successResponse(
                'exist'
            );
        }

        if(is_int($result)){
            return $this->successResponse(
                'finish',
                ucfirst($coordinate),
                'Well done! You completed the game in ' . $result . ' shots'
            );
        }

        return $this->successResponse(
            $result,
            ucfirst($coordinate),
            ucfirst($result)
        );
    }

    public function getGameData()
    {
        $gameId = $this->session->get('game_id');
        if ($gameId === null) {
            return $this->successResponse('new');
        }

        $data = $this->gameRepository->getGameData($gameId);
        $result = [];
        foreach($data['data'] as $row){
            $result[] = $this->parseResponseShots($row);
        }

        return $this->successResponse(
            'data',
            $result
        );
    }

    public function newGame()
    {
        $shipsContainer = $this->gameLogic->init();
        $gameId = $this->gameRepository->saveNewGame($shipsContainer);
        $this->session->set('game_id', $gameId);

        return $this->successResponse('init');
    }

    protected function parseShot($shot)
    {

        $data = str_split($shot);

        $data[0] = array_search(strtoupper($data[0]), $this->row) + 1;

        return implode(',', $data);
    }

    protected function parseResponseShots($data){

        $shot = explode(',', $data['shot']);
        $result['item'] = strtoupper($this->row[$shot[0]-1]) . $shot[1];
        $result['result'] = $data['result'];

        return $result;
    }
}
