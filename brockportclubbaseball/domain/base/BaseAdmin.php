<?php

namespace domain\base;

 /**
  * This is a generated class. Do not edit.
  */
class BaseAdmin extends \domain\DomainObject {
    const ADMINID = "adminId";
    const ADMINNAME = "adminName";
    const ADMINPASSWORD = "adminPassword";
    
    function __construct($registerNew=true){
        self::addAttributes();
        
        if($registerNew === true) {
            $this->isNew = true;
            \base\Registry::getUnitOfWork()->registerNew($this);
        }
    }

    protected function addAttributes() {
        $this->addAttribute(BaseAdmin::ADMINID);
$this->addAttribute(BaseAdmin::ADMINNAME);
$this->addAttribute(BaseAdmin::ADMINPASSWORD);

    }

    public function getTable() {
        return "{{TABLE_NAME}}";
    }

        public function getAdminId() {
        return $this->findAttribute(BaseAdmin::ADMINID)->getValue();
    }

    public function setAdminId($val) {
        $adminId = $this->findAttribute(BaseAdmin::ADMINID);
        $oldValue = $adminId->getValue();
        $adminId->setValue($val);
        if($oldValue !== $val && $this->isNew === false) {
            $adminId->setChanged(true);
            \base\Registry::getUnitOfWork()->registerDirty($this);
        }
    }

    public function getAdminName() {
        return $this->findAttribute(BaseAdmin::ADMINNAME)->getValue();
    }

    public function setAdminName($val) {
        $adminName = $this->findAttribute(BaseAdmin::ADMINNAME);
        $oldValue = $adminName->getValue();
        $adminName->setValue($val);
        if($oldValue !== $val && $this->isNew === false) {
            $adminName->setChanged(true);
            \base\Registry::getUnitOfWork()->registerDirty($this);
        }
    }

    public function getAdminPassword() {
        return $this->findAttribute(BaseAdmin::ADMINPASSWORD)->getValue();
    }

    public function setAdminPassword($val) {
        $adminPassword = $this->findAttribute(BaseAdmin::ADMINPASSWORD);
        $oldValue = $adminPassword->getValue();
        $adminPassword->setValue($val);
        if($oldValue !== $val && $this->isNew === false) {
            $adminPassword->setChanged(true);
            \base\Registry::getUnitOfWork()->registerDirty($this);
        }
    }


}
?>