<?php
/**
 * Created by PhpStorm.
 * User: Justin
 * Date: 7/19/14
 * Time: 2:15 PM
 */

namespace mapper;


use base\ApplicationRegistry;
use base\UnitOfWork;

class QueryObject {
    private $_klass;
    private $_uow;
    private $_criteria = array();
    private $_specificFields;
    private $_joinName;
    private $_endingConditions;

    function __construct($klass, array $specifcFields=null, $joinName=null) {
        $this->_klass = $klass;
        $this->_joinName = $joinName;
        if($specifcFields !== null && count($specifcFields) > 0) {
            $this->_specificFields = $specifcFields;
        }
    }

    /**
     * @param Criteria $criteria
     */
    public function addCriteria(Criteria $criteria) {
        $this->_criteria[] = $criteria;
    }

    /**
     * @access public
     * @param UnitOfWork $uow
     * @return Collection
     */
    public function execute(UnitOfWork $uow) {
        $this->_uow = $uow;
        $m = $uow->getMapper($this->_klass);
        if($this->_joinName !== null) {
            $m->setJoinName($this->_joinName);
        }
        return $m->findObjectsWhere($this->generateWhereClause(), $uow);
    }
    /**
     * @access public
     * @param UnitOfWork $uow
     * @return DomainObject
     */
    public function findOne(UnitOfWork $uow) {
        $this->_uow = $uow;
        $m = $uow->getMapper($this->_klass);
        if($this->_joinName !== null) {
            $m->setJoinName($this->_joinName);
        }
        return $m->findOne($this->generateWhereClause(), $uow);
    }

    /**
     * @access public
     * @param UnitOfWork $uow
     * @param $id
     * @return \domain\DomainObject
     */
    public function findObjectById(UnitOfWork $uow, $id) {
        $this->_uow = $uow;
        $m = $uow->getMapper($this->_klass);
        if($this->_joinName !== null) {
            $m->setJoinName($this->_joinName);
        }
        return $m->findObjectById($id, $uow);
    }

    public function findDataOnly(UnitOfWork $uow, $dataOnlyTable = null, $joinTable = null) {
        $m = $uow->getMapper($this->_klass);
        list($specificFields, $whereClause, $preparedValues) = $this->generateWhereClause();

        if($dataOnlyTable !== null) {
            $sql = "SELECT " . $this->generateColumnList($specificFields) . " FROM " . $dataOnlyTable . $whereClause;
        } else if($joinTable !== null) {
            $tableNames = $m->getDomainObjectTableName() .", ".$joinTable;
            $sql = "SELECT " . $this->generateColumnList($specificFields) . " FROM " . $tableNames . $whereClause;
        } else {
            $sql = "SELECT " . $this->generateColumnList($specificFields) . " FROM " . $m->getDomainObjectTableName() . $whereClause;
        }

        $pdo = ApplicationRegistry::getDSN();
        $stmt = $pdo->prepare($sql);
        $stmt->execute($preparedValues);
        return $stmt->fetchAll();
    }

    /**
     * @param array $columnArray
     * @return string
     */
    private function generateColumnList(array $columnArray=null) {
        if($columnArray === null || count($columnArray) === 0) {
            return "*";
        } else {
            return implode(", ", $columnArray);
        }
    }

    /**
     * @param $endingConditions
     */
    public function setEndingConditions($endingConditions) {
        $this->_endingConditions = $endingConditions;
    }

    /**
     * @return array
     */
    private function generateWhereClause() {
        $whereClause = "";
        $preparedValues = array();
        foreach($this->_criteria as $criteria) {
            list($where, $value, $toAnd) = $criteria->generateSql();
            if($criteria->getIsPrepared() === true) {
                array_push($preparedValues, $value);
            }

            if($whereClause !== "") {
                if($toAnd === true) {
                    $whereClause .= " AND ".$where;
                } else {
                    $whereClause .= " OR ".$where;
                }
            } else {
                $whereClause .= $where;
            }
        }

        if(count($this->_criteria) > 0) {
            $whereClause = " WHERE ".$whereClause;
        }

        if($this->_endingConditions !== null) {
            $whereClause .= " ".$this->_endingConditions;
        }

        return array($this->_specificFields, $whereClause, $preparedValues);
    }
}
?>