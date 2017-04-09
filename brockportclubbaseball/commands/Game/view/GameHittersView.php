<?php

namespace commands\Game\view;

use \view\View as View,
    \view\IView as IView,
    \view\ViewElement as ViewElement,
    \view\ViewEmptyElement as ViewEmptyElement;

/**
  * This is a generated class. Do not edit.
  */
class GameHittersView extends View implements IView {
    private $_commandName = "Game";

    public $loggerElement;
    public $noHittersMessage;
    public $addHittersForm;
    public $hittersTableBody;
    public $playersPopUpList;

    function __construct(){
        $this->loggerElement = new ViewElement("loggerElement");

        $this->noHittersMessage = new ViewElement("noHittersMessage");

        $this->addHittersForm = new ViewElement("addHittersForm");

        $this->hittersTableBody = new ViewElement("hittersTableBody");

        $this->playersPopUpList = new ViewElement("playersPopUpList");


    }

    public function getViewFile() {
        return "gameHittersView.html";
    }

    public function getViewElements() {
        $arr = array($this->loggerElement,$this->noHittersMessage,$this->addHittersForm,$this->hittersTableBody,$this->playersPopUpList);
        return $arr;
    }

    public function getCommandName() {
        return $this->_commandName;
    }

}
?>