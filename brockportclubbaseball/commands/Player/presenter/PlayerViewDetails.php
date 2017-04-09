<?php
/**
 * Created by PhpStorm.
 * User: Justin
 * Date: 7/19/14
 * Time: 6:33 PM
 */

namespace commands\Player\presenter;


class PlayerViewDetails {
    private $_formSubmitted = false;
    private $_errors;
    private $nameAdded = null;

    /**
     * @param boolean $formSubmitted
     */
    public function setFormSubmitted($formSubmitted) {
        $this->_formSubmitted = $formSubmitted;
    }

    /**
     * @return boolean
     */
    public function getFormSubmitted() {
        return $this->_formSubmitted;
    }

    /**
     * @param string $errors
     */
    public function setErrors($errors) {
        $this->_errors = $errors;
    }

    /**
     * @return string
     */
    public function getErrors() {
        return $this->_errors;
    }

    /**
     * @param string $nameAdded
     */
    public function setNameAdded($nameAdded) {
        $this->nameAdded = $nameAdded;
    }

    /**
     * @return string
     */
    public function getNameAdded() {
        return $this->nameAdded;
    }
}
?>