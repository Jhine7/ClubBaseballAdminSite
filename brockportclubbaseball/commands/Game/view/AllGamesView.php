<?php

namespace commands\Game\view;

use \view\View as View,
    \view\IView as IView,
    \view\ViewElement as ViewElement,
    \view\ViewEmptyElement as ViewEmptyElement;

/**
  * This is a generated class. Do not edit.
  */
class AllGamesView extends View implements IView {
    private $_commandName = "Game";

    public $loggerElement;
    public $noGamesAddedMessage;
    public $gamesList;

    function __construct(){
        $this->loggerElement = new ViewElement("loggerElement");

        $this->noGamesAddedMessage = new ViewElement("noGamesAddedMessage");

        $this->gamesList = new ViewElement("gamesList");


    }

    public function getViewFile() {
        return "allGamesView.html";
    }

    public function getViewElements() {
        $arr = array($this->loggerElement,$this->noGamesAddedMessage,$this->gamesList);
        return $arr;
    }

    public function getCommandName() {
        return $this->_commandName;
    }

}
?>