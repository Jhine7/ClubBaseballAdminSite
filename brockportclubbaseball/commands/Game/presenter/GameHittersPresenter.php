<?php
/**
 * Created by PhpStorm.
 * User: Justin
 * Date: 7/19/14
 * Time: 10:40 PM
 */

namespace commands\Game\presenter;


use commands\common\IPresenter;
use commands\Game\view\GameHittersTableRowView;
use commands\Game\view\GameHittersView;
use commands\Game\view\PlayerPopUpListItemView;
use domain\Player;

class GameHittersPresenter implements IPresenter {
    protected $viewDetails;

    function __construct(GameHittersViewDetails $gameHittersViewDetails) {
        $this->viewDetails = $gameHittersViewDetails;
    }

    function create() {
        $gameHittersView = new GameHittersView();

        $hittersId = array();// will hold the ids of the hitters who are currently in the table

        $this->attachHittersTable($gameHittersView, $hittersId);

        $this->attachPlayerPopUp($gameHittersView, $hittersId);

        echo $gameHittersView->create();
    }

    private function attachPlayerPopUp(GameHittersView $gameHittersView, array &$hittersIdArray) {
        $fullContent = null;
        foreach($this->viewDetails->getPlayers() as $player) {
            $playerPopUpListItemView = new PlayerPopUpListItemView();
            $playerPopUpListItemView->playerName->setContent($player->getPlayerFirstName(). " " . $player->getPlayerLastName());
            $playerPopUpListItemView->playerName->removeIdAfterCreation();
            $playerPopUpListItemView->playerId->setAttribute("value", $player->getPlayerId());
            if(in_array($player->getPlayerId(), $hittersIdArray)) {
                $playerPopUpListItemView->playerId->setAttribute("checked", "checked");
            }
            $playerPopUpListItemView->playerId->removeIdAfterCreation();

            $fullContent .= $playerPopUpListItemView->create();
        }

        $gameHittersView->playersPopUpList->appendContent($fullContent);
    }

    private function attachHittersTable(GameHittersView $gameHittersView, array &$hittersIdArray) {
        if(count($this->viewDetails->getHitters()) === 0) {// no hitters added for this game yet
            $gameHittersView->addHittersForm->remove();
        } else {
            $gameHittersView->noHittersMessage->remove();
            $fullContent = "";
            foreach($this->viewDetails->getHitters() as $hitterArray) {
                $gameHittersTableRowView = new GameHittersTableRowView();

                $name = Player::getFullNameFromPlayerId($hitterArray['playerId']);

                array_push($hittersIdArray, $hitterArray['playerId']);

                $gameHittersTableRowView->rowId->setAttribute("id", $hitterArray['playerId']."-Stats");
                $gameHittersTableRowView->name->appendContent($name);
                $gameHittersTableRowView->name->removeIdAfterCreation();
                $gameHittersTableRowView->hiddenCellData->setAttribute("value", $hitterArray['playerId']);
                $gameHittersTableRowView->hiddenCellData->removeIdAfterCreation();
                $gameHittersTableRowView->g->appendAttribute("name", $hitterArray['playerId']);
                $gameHittersTableRowView->g->setAttribute("value", $hitterArray['g']);
                $gameHittersTableRowView->g->removeIdAfterCreation();
                $gameHittersTableRowView->ab->appendAttribute("name", $hitterArray['playerId']);
                $gameHittersTableRowView->ab->setAttribute("value", $hitterArray['ab']);
                $gameHittersTableRowView->ab->removeIdAfterCreation();
                $gameHittersTableRowView->r->appendAttribute("name", $hitterArray['playerId']);
                $gameHittersTableRowView->r->setAttribute("value", $hitterArray['r']);
                $gameHittersTableRowView->r->removeIdAfterCreation();
                $gameHittersTableRowView->h->appendAttribute("name", $hitterArray['playerId']);
                $gameHittersTableRowView->h->setAttribute("value", $hitterArray['h']);
                $gameHittersTableRowView->h->removeIdAfterCreation();
                $gameHittersTableRowView->b2->appendAttribute("name", $hitterArray['playerId']);
                $gameHittersTableRowView->b2->setAttribute("value", $hitterArray['2b']);
                $gameHittersTableRowView->b2->removeIdAfterCreation();
                $gameHittersTableRowView->b3->appendAttribute("name", $hitterArray['playerId']);
                $gameHittersTableRowView->b3->setAttribute("value", $hitterArray['3b']);
                $gameHittersTableRowView->b3->removeIdAfterCreation();
                $gameHittersTableRowView->hr->appendAttribute("name", $hitterArray['playerId']);
                $gameHittersTableRowView->hr->setAttribute("value", $hitterArray['hr']);
                $gameHittersTableRowView->hr->removeIdAfterCreation();
                $gameHittersTableRowView->rbi->appendAttribute("name", $hitterArray['playerId']);
                $gameHittersTableRowView->rbi->setAttribute("value", $hitterArray['rbi']);
                $gameHittersTableRowView->rbi->removeIdAfterCreation();
                $gameHittersTableRowView->bb->appendAttribute("name", $hitterArray['playerId']);
                $gameHittersTableRowView->bb->setAttribute("value", $hitterArray['bb']);
                $gameHittersTableRowView->bb->removeIdAfterCreation();
                $gameHittersTableRowView->so->appendAttribute("name", $hitterArray['playerId']);
                $gameHittersTableRowView->so->setAttribute("value", $hitterArray['so']);
                $gameHittersTableRowView->so->removeIdAfterCreation();
                $gameHittersTableRowView->sb->appendAttribute("name", $hitterArray['playerId']);
                $gameHittersTableRowView->sb->setAttribute("value", $hitterArray['sb']);
                $gameHittersTableRowView->sb->removeIdAfterCreation();
                $gameHittersTableRowView->cs->appendAttribute("name", $hitterArray['playerId']);
                $gameHittersTableRowView->cs->setAttribute("value", $hitterArray['cs']);
                $gameHittersTableRowView->cs->removeIdAfterCreation();
                $gameHittersTableRowView->ibb->appendAttribute("name", $hitterArray['playerId']);
                $gameHittersTableRowView->ibb->setAttribute("value", $hitterArray['ibb']);
                $gameHittersTableRowView->ibb->removeIdAfterCreation();
                $gameHittersTableRowView->hbp->appendAttribute("name", $hitterArray['playerId']);
                $gameHittersTableRowView->hbp->setAttribute("value", $hitterArray['hbp']);
                $gameHittersTableRowView->hbp->removeIdAfterCreation();
                $gameHittersTableRowView->sacb->appendAttribute("name", $hitterArray['playerId']);
                $gameHittersTableRowView->sacb->setAttribute("value", $hitterArray['sacb']);
                $gameHittersTableRowView->sacb->removeIdAfterCreation();
                $gameHittersTableRowView->sacf->appendAttribute("name", $hitterArray['playerId']);
                $gameHittersTableRowView->sacf->setAttribute("value", $hitterArray['sacf']);
                $gameHittersTableRowView->sacf->removeIdAfterCreation();


                $fullContent .= $gameHittersTableRowView->create();
            }

            $gameHittersView->hittersTableBody->appendContent($fullContent);
        }
    }
}
?>