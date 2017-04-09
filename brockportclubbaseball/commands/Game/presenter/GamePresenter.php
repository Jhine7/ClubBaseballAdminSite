<?php
/**
 * Created by PhpStorm.
 * User: Justin
 * Date: 7/19/14
 * Time: 9:53 PM
 */

namespace commands\Game\presenter;


use commands\common\IPresenter;
use commands\Game\view\GameView;

class GamePresenter implements IPresenter {
    protected $viewDetails;

    function __construct(GameViewDetails $gameViewDetails) {
        $this->viewDetails = $gameViewDetails;
    }

    function create() {
        $gameView = new GameView();

        $game = $this->viewDetails->getGame();
        if($game !== null) {
            $gameView->errorSec->remove();

            $winOrLoss = $game->getWon() === "1" ? "W" : "L";
            $gameView->gameName->setContent($game->getVs(). " - " . $game->getMonth()."/".$game->getDay()."/".$game->getYear()." - " . $winOrLoss. ":");

            $gameView->pitcherLink->appendAttribute("href", $game->getGameId()."/pitchers");
            $gameView->hitterLink->appendAttribute("href", $game->getGameId()."/hitters");
        } else {
            $gameView->homeTopRow->remove();
            $gameView->gameName->remove();
        }

        echo $gameView->create();
    }
}
?>