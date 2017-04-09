<?php

namespace commands\Game\view;

use \view\View as View,
    \view\IView as IView,
    \view\ViewElement as ViewElement,
    \view\ViewEmptyElement as ViewEmptyElement;

/**
  * This is a generated class. Do not edit.
  */
class GameView extends View implements IView {
    private $_commandName = "Game";

    public $loggerElement;
    public $errorSec;
    public $gameName;
    public $homeTopRow;
    public $pitcherLink;
    public $hitterLink;

    function __construct(){
        $this->loggerElement = new ViewElement("loggerElement");

        $this->errorSec = new ViewElement("errorSec");

        $this->gameName = new ViewElement("gameName");

        $this->homeTopRow = new ViewElement("homeTopRow");

        $this->pitcherLink = new ViewElement("pitcherLink");

        $this->hitterLink = new ViewElement("hitterLink");


    }

    public function getViewFile() {
        return "gameView.html";
    }

    public function getViewElements() {
        $arr = array($this->loggerElement,$this->errorSec,$this->gameName,$this->homeTopRow,$this->pitcherLink,$this->hitterLink);
        return $arr;
    }

    public function getCommandName() {
        return $this->_commandName;
    }

}
?>