<?php

\Libs\DI::bind("App\Models\Game\GameModelInterface", function () {
    $db = \Libs\DB::getInstance();
    return new \App\Models\Game\GameModel(
        new \App\Domain\Logic\GameLogic(
            new \App\Domain\Factories\ShipFactory()
        ),
        new \App\Domain\Repository\Game\GameRepository(
            new App\Persistence\Database\GamePersistence($db),
            new App\Persistence\Database\ShotPersistence($db)
        ),
        \Libs\Session::getInstance()
    );
});