<?php
/**
 * Created by PhpStorm.
 * User: Justin
 * Date: 7/19/14
 * Time: 7:31 PM
 */

namespace commands\Game\presenter;


class AddGameViewDetails {
    private $_formSubmitted = false;
    private $_errors = null;
    private $_gameAdded = null;

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
    public function setGameAdded($nameAdded) {
        $this->_gameAdded = $nameAdded;
    }

    /**
     * @return string
     */
    public function getGameAdded() {
        return $this->_gameAdded;
    }
}
?>