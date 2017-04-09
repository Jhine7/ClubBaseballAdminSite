<?php
/**
 * Created by PhpStorm.
 * User: Justin
 * Date: 7/19/14
 * Time: 2:13 PM
 */

namespace domain;


use base\Registry;

abstract class DomainObject {
    private $attributes = array();
    protected $attributesAdded = false;

    protected $isNew = false;

    protected function addAttribute($attr) {
        if(!isset($this->attributes[$attr]) || !$this->attributes[$attr]) {
            $this->attributes[$attr] = new Attribute($attr);
        }
    }

    /**
     * @param $attr
     * @return \domain\Attribute
     */
    public function findAttribute($attr) {
        return (isset($this->attributes[$attr]) ? $this->attributes[$attr] : new Attribute("undefined"));// undefined shouldn't happen
    }

    public function getId() {

    }

    public function delete() {
        Registry::getUnitOfWork()->registerRemoved($this);
    }

    /**
     * @return bool
     */
    public function doInsert() {
        $mapper = $this->getMapper(str_replace("domain\\", "", get_class($this)));
        $result = $mapper->doInsert($this);
        $this->isNew = false;
        return $result;
    }

    /**
     * Gets Database Mapper object specified by $klass
     * @access public
     * @param string $klass
     * @return \mapper\Mapper
     */
    private function getMapper($klass) {
        return Registry::getMapper($klass);
    }

    abstract protected function addAttributes();
}
?>