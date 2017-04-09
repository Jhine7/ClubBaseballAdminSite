<?php
/**
 * Created by PhpStorm.
 * User: Justin
 * Date: 7/19/14
 * Time: 6:31 PM
 */

namespace commands\Player\presenter;


use commands\common\IPresenter;
use commands\Player\view\PlayerView;

class PlayerPresenter implements IPresenter {
    protected $viewDetails;

    function __construct(PlayerViewDetails $playerViewDetails) {
        $this->viewDetails = $playerViewDetails;
    }

    function create() {
        $playerView = new PlayerView();

        if($this->viewDetails->getFormSubmitted()) {
            if($this->viewDetails->getErrors() !== null) {
                $playerView->errorSec->setContent($this->viewDetails->getErrors());
                $playerView->confirmSec->remove();
            } else {//success
                $playerView->errorSec->remove();
                $playerView->confirmSec->setContent($this->viewDetails->getNameAdded() . " added successfully!");
            }
        } else {
            $playerView->errorSec->remove();
            $playerView->confirmSec->remove();
        }

        echo $playerView->create();
    }
}
?>