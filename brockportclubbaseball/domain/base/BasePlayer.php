<?php

namespace domain\base;

 /**
  * This is a generated class. Do not edit.
  */
class BasePlayer extends \domain\DomainObject {
    const PLAYERID = "playerId";
    const PLAYERFIRSTNAME = "playerFirstName";
    const PLAYERLASTNAME = "playerLastName";
    
    function __construct($registerNew=true){
        self::addAttributes();
        
        if($registerNew === true) {
            $this->isNew = true;
            \base\Registry::getUnitOfWork()->registerNew($this);
        }
    }

    protected function addAttributes() {
        $this->addAttribute(BasePlayer::PLAYERID);
$this->addAttribute(BasePlayer::PLAYERFIRSTNAME);
$this->addAttribute(BasePlayer::PLAYERLASTNAME);

    }

    public function getTable() {
        return "{{TABLE_NAME}}";
    }

        public function getPlayerId() {
        return $this->findAttribute(BasePlayer::PLAYERID)->getValue();
    }

    public function setPlayerId($val) {
        $playerId = $this->findAttribute(BasePlayer::PLAYERID);
        $oldValue = $playerId->getValue();
        $playerId->setValue($val);
        if($oldValue !== $val && $this->isNew === false) {
            $playerId->setChanged(true);
            \base\Registry::getUnitOfWork()->registerDirty($this);
        }
    }

    public function getPlayerFirstName() {
        return $this->findAttribute(BasePlayer::PLAYERFIRSTNAME)->getValue();
    }

    public function setPlayerFirstName($val) {
        $playerFirstName = $this->findAttribute(BasePlayer::PLAYERFIRSTNAME);
        $oldValue = $playerFirstName->getValue();
        $playerFirstName->setValue($val);
        if($oldValue !== $val && $this->isNew === false) {
            $playerFirstName->setChanged(true);
            \base\Registry::getUnitOfWork()->registerDirty($this);
        }
    }

    public function getPlayerLastName() {
        return $this->findAttribute(BasePlayer::PLAYERLASTNAME)->getValue();
    }

    public function setPlayerLastName($val) {
        $playerLastName = $this->findAttribute(BasePlayer::PLAYERLASTNAME);
        $oldValue = $playerLastName->getValue();
        $playerLastName->setValue($val);
        if($oldValue !== $val && $this->isNew === false) {
            $playerLastName->setChanged(true);
            \base\Registry::getUnitOfWork()->registerDirty($this);
        }
    }


}
?>