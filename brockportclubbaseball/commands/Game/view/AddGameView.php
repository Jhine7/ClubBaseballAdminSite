<?php

namespace commands\Game\view;

use \view\View as View,
    \view\IView as IView,
    \view\ViewElement as ViewElement,
    \view\ViewEmptyElement as ViewEmptyElement;

/**
  * This is a generated class. Do not edit.
  */
class AddGameView extends View implements IView {
    private $_commandName = "Game";

    public $loggerElement;
    public $errorSec;
    public $confirmSec;

    function __construct(){
        $this->loggerElement = new ViewElement("loggerElement");

        $this->errorSec = new ViewElement("errorSec");

        $this->confirmSec = new ViewElement("confirmSec");


    }

    public function getViewFile() {
        return "addGameView.html";
    }

    public function getViewElements() {
        $arr = array($this->loggerElement,$this->errorSec,$this->confirmSec);
        return $arr;
    }

    public function getCommandName() {
        return $this->_commandName;
    }

}
?>