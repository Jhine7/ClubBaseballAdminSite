<?php

namespace commands\Player\view;

use \view\View as View,
    \view\IView as IView,
    \view\ViewElement as ViewElement,
    \view\ViewEmptyElement as ViewEmptyElement;

/**
  * This is a generated class. Do not edit.
  */
class PlayerView extends View implements IView {
    private $_commandName = "Player";

    public $loggerElement;
    public $errorSec;
    public $confirmSec;

    function __construct(){
        $this->loggerElement = new ViewElement("loggerElement");

        $this->errorSec = new ViewElement("errorSec");

        $this->confirmSec = new ViewElement("confirmSec");


    }

    public function getViewFile() {
        return "playerView.html";
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