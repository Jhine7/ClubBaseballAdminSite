<?php
/**
 * Created by PhpStorm.
 * User: Justin
 * Date: 7/19/14
 * Time: 2:11 PM
 */

namespace mapper;


class Criteria {
    private $_sqlOperator;
    private $_field;
    private $_value;
    private $_isPrepared;
    private $_andStatement;

    function __construct($sqlOperator, $field, $value, $isPrepared = true, $andStatement = true) {
        $this->_sqlOperator = $sqlOperator;
        $this->_field = $field;
        $this->_value = $value;
        $this->_isPrepared = $isPrepared;
        $this->_andStatement = $andStatement;
    }

    public function generateSql() {
        $value = null;
        if($this->_isPrepared === true) {
            $whereClause = "{$this->_field} {$this->_sqlOperator} ?";
            $value = $this->_value;
        } else {
            $whereClause = "{$this->_field} {$this->_sqlOperator} {$this->_value}";
        }

        return array($whereClause, $value, $this->_andStatement);
    }

    public function getIsPrepared() {
        return $this->_isPrepared;
    }

    // Convenience static methods

    public static function greaterThan($fieldName, $value, $isPrepared=true, $andStatement=true) {
        return new Criteria(">", $fieldName, $value, $isPrepared, $andStatement);
    }

    public static function greaterThanEqualTo($fieldName, $value, $isPrepared=true, $andStatement=true) {
        return new Criteria(">=", $fieldName, $value, $isPrepared, $andStatement);
    }

    public static function lessThan($fieldName, $value, $isPrepared=true, $andStatement=true) {
        return new Criteria("<", $fieldName, $value, $isPrepared, $andStatement);
    }

    public static function lessThanEqualTo($fieldName, $value, $isPrepared=true, $andStatement=true) {
        return new Criteria("<=", $fieldName, $value, $isPrepared, $andStatement);
    }

    public static function equals($fieldName, $value, $isPrepared=true, $andStatement=true) {
        return new Criteria("=", $fieldName, $value, $isPrepared, $andStatement);
    }

    public static function notEquals($fieldName, $value, $isPrepared=true, $andStatement=true) {
        return new Criteria("!=", $fieldName, $value, $isPrepared, $andStatement);
    }

    public static function like($fieldName, $value, $isPrepared=true, $andStatement=true) {
        return new Criteria("LIKE", $fieldName, $value, $isPrepared, $andStatement);
    }

    public static function isNull($fieldName, $value=null, $isPrepared=false, $andStatement=true) {
        return new Criteria("IS NULL", $fieldName, $value, $isPrepared, $andStatement);
    }

    public static function isNotNull($fieldName, $value=null, $isPrepared=false, $andStatement=true) {
        return new Criteria("IS NOT NULL", $fieldName, $value, $isPrepared, $andStatement);
    }

    public static function notIn($fieldName, $value=null, $isPrepared=false, $andStatement=true) {
        return new Criteria("NOT IN", $fieldName, $value, $isPrepared, $andStatement);
    }
}
?>