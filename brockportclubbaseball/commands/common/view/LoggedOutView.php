<?php

namespace commands\common\view;

use \view\View as View,
    \view\IView as IView,
    \view\ViewElement as ViewElement,
    \view\ViewEmptyElement as ViewEmptyElement;

/**
  * This is a generated class. Do not edit.
  */
class LoggedOutView extends View implements IView {
    private $_commandName = "common";

    public $loggerElement;

    function __construct(){
        $this->loggerElement = new ViewElement("loggerElement");


    }

    public function getViewFile() {
        return "loggedOutView.html";
    }

    public function getViewElements() {
        $arr = array($this->loggerElement);
        return $arr;
    }

    public function getCommandName() {
        return $this->_commandName;
    }

}
?>