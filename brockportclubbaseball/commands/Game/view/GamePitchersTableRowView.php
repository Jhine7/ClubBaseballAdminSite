<?php

namespace commands\Game\view;

use \view\View as View,
    \view\IView as IView,
    \view\ViewElement as ViewElement,
    \view\ViewEmptyElement as ViewEmptyElement;

/**
  * This is a generated class. Do not edit.
  */
class GamePitchersTableRowView extends View implements IView {
    private $_commandName = "Game";

    public $rowId;
    public $name;
    public $hiddenCellData;
    public $w;
    public $l;
    public $g;
    public $gs;
    public $cg;
    public $sv;
    public $svo;
    public $ip;
    public $h;
    public $r;
    public $er;
    public $hr;
    public $bb;
    public $so;
    public $sho;
    public $hbp;

    function __construct(){
        $this->rowId = new ViewElement("rowId");

        $this->name = new ViewElement("name");

        $this->hiddenCellData = new ViewElement("hiddenCellData");

        $this->w = new ViewElement("w");

        $this->l = new ViewElement("l");

        $this->g = new ViewElement("g");

        $this->gs = new ViewElement("gs");

        $this->cg = new ViewElement("cg");

        $this->sv = new ViewElement("sv");

        $this->svo = new ViewElement("svo");

        $this->ip = new ViewElement("ip");

        $this->h = new ViewElement("h");

        $this->r = new ViewElement("r");

        $this->er = new ViewElement("er");

        $this->hr = new ViewElement("hr");

        $this->bb = new ViewElement("bb");

        $this->so = new ViewElement("so");

        $this->sho = new ViewElement("sho");

        $this->hbp = new ViewElement("hbp");


    }

    public function getViewFile() {
        return "gamePitchersTableRowView.html";
    }

    public function getViewElements() {
        $arr = array($this->rowId,$this->name,$this->hiddenCellData,$this->w,$this->l,$this->g,$this->gs,$this->cg,$this->sv,$this->svo,$this->ip,$this->h,$this->r,$this->er,$this->hr,$this->bb,$this->so,$this->sho,$this->hbp);
        return $arr;
    }

    public function getCommandName() {
        return $this->_commandName;
    }

}
?>