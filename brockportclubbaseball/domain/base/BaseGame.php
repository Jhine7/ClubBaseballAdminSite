<?php

namespace domain\base;

 /**
  * This is a generated class. Do not edit.
  */
class BaseGame extends \domain\DomainObject {
    const GAMEID = "gameId";
    const VS = "vs";
    const MONTH = "month";
    const DAY = "day";
    const YEAR = "year";
    const WON = "won";
    
    function __construct($registerNew=true){
        self::addAttributes();
        
        if($registerNew === true) {
            $this->isNew = true;
            \base\Registry::getUnitOfWork()->registerNew($this);
        }
    }

    protected function addAttributes() {
        $this->addAttribute(BaseGame::GAMEID);
$this->addAttribute(BaseGame::VS);
$this->addAttribute(BaseGame::MONTH);
$this->addAttribute(BaseGame::DAY);
$this->addAttribute(BaseGame::YEAR);
$this->addAttribute(BaseGame::WON);

    }

    public function getTable() {
        return "{{TABLE_NAME}}";
    }

        public function getGameId() {
        return $this->findAttribute(BaseGame::GAMEID)->getValue();
    }

    public function setGameId($val) {
        $gameId = $this->findAttribute(BaseGame::GAMEID);
        $oldValue = $gameId->getValue();
        $gameId->setValue($val);
        if($oldValue !== $val && $this->isNew === false) {
            $gameId->setChanged(true);
            \base\Registry::getUnitOfWork()->registerDirty($this);
        }
    }

    public function getVs() {
        return $this->findAttribute(BaseGame::VS)->getValue();
    }

    public function setVs($val) {
        $vs = $this->findAttribute(BaseGame::VS);
        $oldValue = $vs->getValue();
        $vs->setValue($val);
        if($oldValue !== $val && $this->isNew === false) {
            $vs->setChanged(true);
            \base\Registry::getUnitOfWork()->registerDirty($this);
        }
    }

    public function getMonth() {
        return $this->findAttribute(BaseGame::MONTH)->getValue();
    }

    public function setMonth($val) {
        $month = $this->findAttribute(BaseGame::MONTH);
        $oldValue = $month->getValue();
        $month->setValue($val);
        if($oldValue !== $val && $this->isNew === false) {
            $month->setChanged(true);
            \base\Registry::getUnitOfWork()->registerDirty($this);
        }
    }

    public function getDay() {
        return $this->findAttribute(BaseGame::DAY)->getValue();
    }

    public function setDay($val) {
        $day = $this->findAttribute(BaseGame::DAY);
        $oldValue = $day->getValue();
        $day->setValue($val);
        if($oldValue !== $val && $this->isNew === false) {
            $day->setChanged(true);
            \base\Registry::getUnitOfWork()->registerDirty($this);
        }
    }

    public function getYear() {
        return $this->findAttribute(BaseGame::YEAR)->getValue();
    }

    public function setYear($val) {
        $year = $this->findAttribute(BaseGame::YEAR);
        $oldValue = $year->getValue();
        $year->setValue($val);
        if($oldValue !== $val && $this->isNew === false) {
            $year->setChanged(true);
            \base\Registry::getUnitOfWork()->registerDirty($this);
        }
    }

    public function getWon() {
        return $this->findAttribute(BaseGame::WON)->getValue();
    }

    public function setWon($val) {
        $won = $this->findAttribute(BaseGame::WON);
        $oldValue = $won->getValue();
        $won->setValue($val);
        if($oldValue !== $val && $this->isNew === false) {
            $won->setChanged(true);
            \base\Registry::getUnitOfWork()->registerDirty($this);
        }
    }


}
?>