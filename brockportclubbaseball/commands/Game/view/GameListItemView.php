<?php

namespace commands\Game\view;

use \view\View as View,
    \view\IView as IView,
    \view\ViewElement as ViewElement,
    \view\ViewEmptyElement as ViewEmptyElement;

/**
  * This is a generated class. Do not edit.
  */
class GameListItemView extends View implements IView {
    private $_commandName = "Game";

    public $gameLink;
    public $gameDate;
    public $gameWinLoss;
    public $gameDetails;

    function __construct(){
        $this->gameLink = new ViewElement("gameLink");

        $this->gameDate = new ViewElement("gameDate");

        $this->gameWinLoss = new ViewElement("gameWinLoss");

        $this->gameDetails = new ViewElement("gameDetails");


    }

    public function getViewFile() {
        return "gameListItemView.html";
    }

    public function getViewElements() {
        $arr = array($this->gameLink,$this->gameDate,$this->gameWinLoss,$this->gameDetails);
        return $arr;
    }

    public function getCommandName() {
        return $this->_commandName;
    }

}
?>