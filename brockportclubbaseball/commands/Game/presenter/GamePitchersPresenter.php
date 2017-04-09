<?php
/**
 * Created by PhpStorm.
 * User: Justin
 * Date: 7/19/14
 * Time: 10:38 PM
 */

namespace commands\Game\presenter;


use commands\common\IPresenter;
use commands\Game\view\GamePitchersTableRowView;
use commands\Game\view\GamePitchersView;
use commands\Game\view\PlayerPopUpListItemView;
use domain\Player;

class GamePitchersPresenter implements IPresenter {
    protected $viewDetails;

    function __construct(GamePitchersViewDetails $gamePitchersViewDetails) {
        $this->viewDetails = $gamePitchersViewDetails;
    }

    function create() {
        $gamePitchersView = new GamePitchersView();

        $pitchersId = array();// will hold the ids of the pitchers who are currently in the table

        $this->attachPitchersTable($gamePitchersView, $pitchersId);

        $this->attachPlayerPopUp($gamePitchersView, $pitchersId);

        echo $gamePitchersView->create();
    }

    private function attachPlayerPopUp(GamePitchersView $gamePitchersView, array &$pitchersIdArray) {
        $fullContent = null;
        foreach($this->viewDetails->getPlayers() as $player) {
            $playerPopUpListItemView = new PlayerPopUpListItemView();
            $playerPopUpListItemView->playerName->setContent($player->getPlayerFirstName(). " " . $player->getPlayerLastName());
            $playerPopUpListItemView->playerName->removeIdAfterCreation();
            $playerPopUpListItemView->playerId->setAttribute("value", $player->getPlayerId());
            if(in_array($player->getPlayerId(), $pitchersIdArray)) {
                $playerPopUpListItemView->playerId->setAttribute("checked", "checked");
            }
            $playerPopUpListItemView->playerId->removeIdAfterCreation();

            $fullContent .= $playerPopUpListItemView->create();
        }

        $gamePitchersView->playersPopUpList->appendContent($fullContent);
    }

    private function attachPitchersTable(GamePitchersView $gamePitchersView, array &$pitchersIdArray) {
        if(count($this->viewDetails->getPitchers()) === 0) {// no pitchers added for this game yet
            $gamePitchersView->addPitchersForm->remove();
        } else {
            $gamePitchersView->noPitchersMessage->remove();
            $fullContent = "";
            foreach($this->viewDetails->getPitchers() as $pitcherArray) {
                $gamePitchersTableRowView = new GamePitchersTableRowView();

                $name = Player::getFullNameFromPlayerId($pitcherArray['playerId']);

                array_push($pitchersIdArray, $pitcherArray['playerId']);

                $gamePitchersTableRowView->rowId->setAttribute("id", $pitcherArray['playerId']."-Stats");
                $gamePitchersTableRowView->name->appendContent($name);
                $gamePitchersTableRowView->name->removeIdAfterCreation();
                $gamePitchersTableRowView->hiddenCellData->setAttribute("value", $pitcherArray['playerId']);
                $gamePitchersTableRowView->hiddenCellData->removeIdAfterCreation();
                $gamePitchersTableRowView->w->appendAttribute("name", $pitcherArray['playerId']);
                $gamePitchersTableRowView->w->setAttribute("value", $pitcherArray['won']);
                $gamePitchersTableRowView->w->removeIdAfterCreation();
                $gamePitchersTableRowView->l->appendAttribute("name", $pitcherArray['playerId']);
                $gamePitchersTableRowView->l->setAttribute("value", $pitcherArray['loss']);
                $gamePitchersTableRowView->l->removeIdAfterCreation();
                $gamePitchersTableRowView->g->appendAttribute("name", $pitcherArray['playerId']);
                $gamePitchersTableRowView->g->setAttribute("value", $pitcherArray['g']);
                $gamePitchersTableRowView->g->removeIdAfterCreation();
                $gamePitchersTableRowView->gs->appendAttribute("name", $pitcherArray['playerId']);
                $gamePitchersTableRowView->gs->setAttribute("value", $pitcherArray['gs']);
                $gamePitchersTableRowView->gs->removeIdAfterCreation();
                $gamePitchersTableRowView->cg->appendAttribute("name", $pitcherArray['playerId']);
                $gamePitchersTableRowView->cg->setAttribute("value", $pitcherArray['cg']);
                $gamePitchersTableRowView->cg->removeIdAfterCreation();
                $gamePitchersTableRowView->sv->appendAttribute("name", $pitcherArray['playerId']);
                $gamePitchersTableRowView->sv->setAttribute("value", $pitcherArray['sv']);
                $gamePitchersTableRowView->sv->removeIdAfterCreation();
                $gamePitchersTableRowView->svo->appendAttribute("name", $pitcherArray['playerId']);
                $gamePitchersTableRowView->svo->setAttribute("value", $pitcherArray['svo']);
                $gamePitchersTableRowView->svo->removeIdAfterCreation();
                $gamePitchersTableRowView->ip->appendAttribute("name", $pitcherArray['playerId']);
                $gamePitchersTableRowView->ip->setAttribute("value", $pitcherArray['ip']);
                $gamePitchersTableRowView->ip->removeIdAfterCreation();
                $gamePitchersTableRowView->h->appendAttribute("name", $pitcherArray['playerId']);
                $gamePitchersTableRowView->h->setAttribute("value", $pitcherArray['h']);
                $gamePitchersTableRowView->h->removeIdAfterCreation();
                $gamePitchersTableRowView->r->appendAttribute("name", $pitcherArray['playerId']);
                $gamePitchersTableRowView->r->setAttribute("value", $pitcherArray['r']);
                $gamePitchersTableRowView->r->removeIdAfterCreation();
                $gamePitchersTableRowView->er->appendAttribute("name", $pitcherArray['playerId']);
                $gamePitchersTableRowView->er->setAttribute("value", $pitcherArray['er']);
                $gamePitchersTableRowView->er->removeIdAfterCreation();
                $gamePitchersTableRowView->hr->appendAttribute("name", $pitcherArray['playerId']);
                $gamePitchersTableRowView->hr->setAttribute("value", $pitcherArray['hr']);
                $gamePitchersTableRowView->hr->removeIdAfterCreation();
                $gamePitchersTableRowView->bb->appendAttribute("name", $pitcherArray['playerId']);
                $gamePitchersTableRowView->bb->setAttribute("value", $pitcherArray['bb']);
                $gamePitchersTableRowView->bb->removeIdAfterCreation();
                $gamePitchersTableRowView->so->appendAttribute("name", $pitcherArray['playerId']);
                $gamePitchersTableRowView->so->setAttribute("value", $pitcherArray['so']);
                $gamePitchersTableRowView->so->removeIdAfterCreation();
                $gamePitchersTableRowView->sho->appendAttribute("name", $pitcherArray['playerId']);
                $gamePitchersTableRowView->sho->setAttribute("value", $pitcherArray['sho']);
                $gamePitchersTableRowView->sho->removeIdAfterCreation();
                $gamePitchersTableRowView->hbp->appendAttribute("name", $pitcherArray['playerId']);
                $gamePitchersTableRowView->hbp->setAttribute("value", $pitcherArray['hbp']);
                $gamePitchersTableRowView->hbp->removeIdAfterCreation();

                $fullContent .= $gamePitchersTableRowView->create();
            }

            $gamePitchersView->pitchersTableBody->appendContent($fullContent);
        }
    }
}
?>