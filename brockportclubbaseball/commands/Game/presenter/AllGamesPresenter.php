<?php
/**
 * Created by PhpStorm.
 * User: Justin
 * Date: 7/19/14
 * Time: 8:28 PM
 */

namespace commands\Game\presenter;


use commands\common\IPresenter;
use commands\Game\view\AllGamesView;
use commands\Game\view\GameListItemView;
use domain\Game;

class AllGamesPresenter implements IPresenter {

    protected $viewDetails;

    function __construct(AllGamesViewDetails $allGamesViewDetails) {
        $this->viewDetails = $allGamesViewDetails;
    }

    function create() {
        $allGamesView = new AllGamesView();

        $allGames = $this->viewDetails->getGames();
        if($allGames->length() > 0) {
            $allGamesView->noGamesAddedMessage->remove();

            $fullContent = "";
            foreach($allGames as $game) {
                $gameListItemView = new GameListItemView();

                $gameListItemView->gameLink->appendAttribute("href", $game->getGameId());
                $gameListItemView->gameLink->removeIdAfterCreation();

                $gameListItemView->gameDate->setContent($game->getMonth()."/".$game->getDay()."/".$game->getYear());
                $gameListItemView->gameDate->removeIdAfterCreation();

                $winOrLoss = $game->getWon() === "1" ? "W" : "L";
                $gameListItemView->gameWinLoss->setContent($winOrLoss);
                $gameListItemView->gameWinLoss->removeIdAfterCreation();

                $gameListItemView->gameDetails->setContent($game->getVs());
                $gameListItemView->gameDetails->removeIdAfterCreation();

                $fullContent .= $gameListItemView->create();
            }

            $allGamesView->gamesList->appendContent($fullContent);
        } else {
            $allGamesView->gamesList->remove();
        }

        echo $allGamesView->create();
    }
}
?>