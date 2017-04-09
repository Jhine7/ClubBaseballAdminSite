<?php

namespace commands\Game\view;

use \view\View as View,
    \view\IView as IView,
    \view\ViewElement as ViewElement,
    \view\ViewEmptyElement as ViewEmptyElement;

/**
  * This is a generated class. Do not edit.
  */
class GameHittersTableRowView extends View implements IView {
    private $_commandName = "Game";

    public $rowId;
    public $name;
    public $hiddenCellData;
    public $g;
    public $ab;
    public $r;
    public $h;
    public $b2;
    public $b3;
    public $hr;
    public $rbi;
    public $bb;
    public $so;
    public $sb;
    public $cs;
    public $ibb;
    public $hbp;
    public $sacb;
    public $sacf;

    function __construct(){
        $this->rowId = new ViewElement("rowId");

        $this->name = new ViewElement("name");

        $this->hiddenCellData = new ViewElement("hiddenCellData");

        $this->g = new ViewElement("g");

        $this->ab = new ViewElement("ab");

        $this->r = new ViewElement("r");

        $this->h = new ViewElement("h");

        $this->b2 = new ViewElement("b2");

        $this->b3 = new ViewElement("b3");

        $this->hr = new ViewElement("hr");

        $this->rbi = new ViewElement("rbi");

        $this->bb = new ViewElement("bb");

        $this->so = new ViewElement("so");

        $this->sb = new ViewElement("sb");

        $this->cs = new ViewElement("cs");

        $this->ibb = new ViewElement("ibb");

        $this->hbp = new ViewElement("hbp");

        $this->sacb = new ViewElement("sacb");

        $this->sacf = new ViewElement("sacf");


    }

    public function getViewFile() {
        return "gameHittersTableRowView.html";
    }

    public function getViewElements() {
        $arr = array($this->rowId,$this->name,$this->hiddenCellData,$this->g,$this->ab,$this->r,$this->h,$this->b2,$this->b3,$this->hr,$this->rbi,$this->bb,$this->so,$this->sb,$this->cs,$this->ibb,$this->hbp,$this->sacb,$this->sacf);
        return $arr;
    }

    public function getCommandName() {
        return $this->_commandName;
    }

}
?>