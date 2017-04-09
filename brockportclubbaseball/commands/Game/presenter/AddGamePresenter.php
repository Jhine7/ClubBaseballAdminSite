<?php
/**
 * Created by PhpStorm.
 * User: Justin
 * Date: 7/19/14
 * Time: 7:31 PM
 */

namespace commands\Game\presenter;


use commands\common\IPresenter;
use commands\Game\view\AddGameView;

class AddGamePresenter implements IPresenter {
    protected $viewDetails;

    function __construct(AddGameViewDetails $addGameViewDetails) {
        $this->viewDetails = $addGameViewDetails;
    }

    function create() {
        $addGameView = new AddGameView();

        if($this->viewDetails->getFormSubmitted()) {
            if($this->viewDetails->getErrors() !== null) {
                $addGameView->errorSec->setContent($this->viewDetails->getErrors());
                $addGameView->confirmSec->remove();
            } else {//success
                $addGameView->errorSec->remove();
                $addGameView->confirmSec->setContent($this->viewDetails->getGameAdded());
            }
        } else {
            $addGameView->errorSec->remove();
            $addGameView->confirmSec->remove();
        }

        echo $addGameView->create();
    }
}
?>