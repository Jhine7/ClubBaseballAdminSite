<?php

namespace commands\Defaults\view;

use \view\View as View,
    \view\IView as IView,
    \view\ViewElement as ViewElement,
    \view\ViewEmptyElement as ViewEmptyElement;

/**
  * This is a generated class. Do not edit.
  */
class HomeView extends View implements IView {
    private $_commandName = "Defaults";

    public $loggerElement;

    function __construct(){
        $this->loggerElement = new ViewElement("loggerElement");


    }

    public function getViewFile() {
        return "homeView.html";
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