<?php
/**
 * Created by PhpStorm.
 * User: Justin
 * Date: 7/19/14
 * Time: 2:14 PM
 */

namespace domain;


class Attribute {
    private $_value;
    private $_changed = false;
    private $_name;

    function __construct($name) {
        $this->_name = $name;
    }

    /**
     * @param mixed $value
     */
    public function setValue($value) {
        $this->_value = $value;
    }

    /**
     * @return mixed
     */
    public function getValue() {
        return $this->_value;
    }

    /**
     * @param boolean $changed
     */
    public function setChanged($changed) {
        $this->_changed = $changed;
    }

    /**
     * @return boolean
     */
    public function getChanged() {
        return $this->_changed;
    }
}
?>