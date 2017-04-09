<?php

namespace commands\Game\view;

use \view\View as View,
    \view\IView as IView,
    \view\ViewElement as ViewElement,
    \view\ViewEmptyElement as ViewEmptyElement;

/**
  * This is a generated class. Do not edit.
  */
class GamePitchersView extends View implements IView {
    private $_commandName = "Game";

    public $loggerElement;
    public $noPitchersMessage;
    public $addPitchersForm;
    public $pitchersTableBody;
    public $playersPopUpList;

    function __construct(){
        $this->loggerElement = new ViewElement("loggerElement");

        $this->noPitchersMessage = new ViewElement("noPitchersMessage");

        $this->addPitchersForm = new ViewElement("addPitchersForm");

        $this->pitchersTableBody = new ViewElement("pitchersTableBody");

        $this->playersPopUpList = new ViewElement("playersPopUpList");


    }

    public function getViewFile() {
        return "gamePitchersView.html";
    }

    public function getViewElements() {
        $arr = array($this->loggerElement,$this->noPitchersMessage,$this->addPitchersForm,$this->pitchersTableBody,$this->playersPopUpList);
        return $arr;
    }

    public function getCommandName() {
        return $this->_commandName;
    }

}
?>