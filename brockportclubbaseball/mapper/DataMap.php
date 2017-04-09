<?php
/**
 * Created by PhpStorm.
 * User: Justin
 * Date: 7/19/14
 * Time: 2:11 PM
 */

namespace mapper;


class DataMap {
    private $_domainClass;
    private $_tableName;
    private $_idField;
    private $_joins = array();

    function __construct($domainClass, $tableName, $idField) {
        $this->_domainClass = $domainClass;
        $this->_tableName = $tableName;
        $this->_idField = $idField;
    }

    /**
     * Holds the various joins a DomainObject could use. $join array's key should be the join name and value should be the table(s)
     * @access public
     * @param array $join
     */
    public function addJoin(array $join) {
        $keys = array_keys($join);
        $values = array_values($join);
        $this->_joins[$keys[0]] = $values[0];
    }

    /**
     * @see addJoin()
     * @param $joinName
     * @return mixed
     */
    public function getJoinTable($joinName) {
        return $this->_joins[$joinName];
    }

    public function getTableName() {
        return $this->_tableName;
    }

    public function getDomainClassName() {
        return $this->_domainClass;
    }

    public function getDomainClassReflectionClass() {
        return new \ReflectionClass("\domain\\".$this->_domainClass);
    }

    public function getIdField() {
        return $this->_idField;
    }
}
?>