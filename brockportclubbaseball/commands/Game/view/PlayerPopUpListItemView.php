<?php

namespace commands\Game\view;

use \view\View as View,
    \view\IView as IView,
    \view\ViewElement as ViewElement,
    \view\ViewEmptyElement as ViewEmptyElement;

/**
  * This is a generated class. Do not edit.
  */
class PlayerPopUpListItemView extends View implements IView {
    private $_commandName = "Game";

    public $playerName;
    public $playerId;

    function __construct(){
        $this->playerName = new ViewElement("playerName");

        $this->playerId = new ViewElement("playerId");


    }

    public function getViewFile() {
        return "playerPopUpListItemView.html";
    }

    public function getViewElements() {
        $arr = array($this->playerName,$this->playerId);
        return $arr;
    }

    public function getCommandName() {
        return $this->_commandName;
    }

}
?>