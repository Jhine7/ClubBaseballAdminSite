<?php
/**
 * Created by PhpStorm.
 * User: Justin
 * Date: 7/19/14
 * Time: 2:12 PM
 */

namespace mapper;


use base\ApplicationRegistry;
use base\UnitOfWork;
use domain\DomainObject;

abstract class Mapper {
    private static $PDO;
    private $statements=array();// caches already executed query statements
    protected $dataMap;
    private $_joinName;
    private $_savedObjectColumns = array();// caches already described tables

    /**
     * @param string $joinName
     */
    public function setJoinName($joinName) {
        $this->_joinName = $joinName;
    }

    function __construct(){
        if(!isset(self::$PDO)){
            self::$PDO = ApplicationRegistry::getDSN();
        }
        $this->loadDataMap();
    }

    public function getDomainObjectIdField() {
        return $this->dataMap->getIdField();
    }

    public function getDomainObjectTableName() {
        return $this->dataMap->getTableName();
    }

    public function update(DomainObject $obj) {
        if(isset($this->_savedObjectColumns[$this->dataMap->getDomainClassName()])) {
            list($sql, $terms) = $this->createUpdateSQL($obj);
            if(!isset($this->statements[$sql])) {
                $this->statements[$sql] = self::$PDO->prepare($sql);
            }
            $stmt = $this->statements[$sql];
            $stmt->execute($terms);
        } else {
            $sql = "DESCRIBE " . $this->dataMap->getTableName();
            if(!isset($this->statements[$sql])) {
                $this->statements[$sql] = self::$PDO->prepare($sql);
            }
            $stmt = $this->statements[$sql];
            $stmt->execute();
            $rs = $stmt->fetchAll();
            $this->_savedObjectColumns[$this->dataMap->getDomainClassName()] = $rs;
            list($sql, $terms) = $this->createUpdateSQL($obj);

            if(!isset($this->statements[$sql])) {
                $this->statements[$sql] = self::$PDO->prepare($sql);
            }
            $stmt = $this->statements[$sql];
            $stmt->execute($terms);
        }
    }

    public function insert(DomainObject $obj) {
        if(isset($this->_savedObjectColumns[$this->dataMap->getDomainClassName()])) {
            list($sql, $terms) = $this->createInsertSQL($obj);
            if(!isset($this->statements[$sql])) {
                $this->statements[$sql] = self::$PDO->prepare($sql);
            }
            $stmt = $this->statements[$sql];
            if($stmt->execute($terms)) {
                return true;
            } else {
                return false;
            }
        } else {
            $sql = "DESCRIBE " . $this->dataMap->getTableName();
            if(!isset($this->statements[$sql])) {
                $this->statements[$sql] = self::$PDO->prepare($sql);
            }
            $stmt = $this->statements[$sql];
            $stmt->execute();
            $rs = $stmt->fetchAll();
            $this->_savedObjectColumns[$this->dataMap->getDomainClassName()] = $rs;
            list($sql, $terms) = $this->createInsertSQL($obj);

            if(!isset($this->statements[$sql])) {
                $this->statements[$sql] = self::$PDO->prepare($sql);
            }
            $stmt = $this->statements[$sql];
            if($stmt->execute($terms)) {
                return true;
            } else {
                return false;
            }
        }
    }

    /**
     * Call for auto-incremented primary keys Domain Objects (all of the mapped Domain Objects should work this way now)
     * @param DomainObject $obj
     * @return bool
     */
    public function doInsert(DomainObject $obj) {
        $idField = $this->dataMap->getIdField();
        $idMethod = "set".ucfirst($idField);

        if(isset($this->_savedObjectColumns[$this->dataMap->getDomainClassName()])) {
            list($sql, $terms) = $this->doCreateInsertSQL($obj);
            if(!isset($this->statements[$sql])) {
                $this->statements[$sql] = self::$PDO->prepare($sql);
            }
            $stmt = $this->statements[$sql];
            if($stmt->execute($terms)) {
                $obj->$idMethod(self::$PDO->lastInsertId());
                return true;
            } else {
                return false;
            }
        } else {
            $sql = "DESCRIBE " . $this->dataMap->getTableName();
            if(!isset($this->statements[$sql])) {
                $this->statements[$sql] = self::$PDO->prepare($sql);
            }
            $stmt = $this->statements[$sql];
            $stmt->execute();
            $rs = $stmt->fetchAll();
            $this->_savedObjectColumns[$this->dataMap->getDomainClassName()] = $rs;
            list($sql, $terms) = $this->doCreateInsertSQL($obj);

            if(!isset($this->statements[$sql])) {
                $this->statements[$sql] = self::$PDO->prepare($sql);
            }
            $stmt = $this->statements[$sql];
            if($stmt->execute($terms)) {
                $obj->$idMethod(self::$PDO->lastInsertId());
                return true;
            } else {
                return false;
            }
        }
    }

    /**
     * @param DomainObject $obj
     * @return array
     */
    private function createInsertSQL(DomainObject $obj) {
        $sql = "INSERT INTO " . $this->dataMap->getTableName() . " (";
        $fieldsToInclude = array();
        foreach($this->_savedObjectColumns[$this->dataMap->getDomainClassName()] as $tableField) {
            if(($attribute = $obj->findAttribute($tableField["Field"])) !== null) {
                if($attribute->getValue() !== null) {
                    array_push($fieldsToInclude, $tableField["Field"]);
                    $terms[] = $attribute->getValue();
                    $qs[] = '?';
                }
            }
        }
        $sql .= implode(",", $fieldsToInclude);
        $sql .= ") VALUES (";
        $sql .= implode(",", $qs);
        $sql .= ")";

        return array($sql, $terms);
    }

    /**
     * This version does not include primary key (idField) in SQL because all of our primary keys are now auto incremented
     * @param DomainObject $obj
     * @return array
     */
    private function doCreateInsertSQL(DomainObject $obj) {
        $idField = $this->dataMap->getIdField();

        $sql = "INSERT INTO " . $this->dataMap->getTableName() . " (";
        $fieldsToInclude = array();
        foreach($this->_savedObjectColumns[$this->dataMap->getDomainClassName()] as $tableField) {
            if(($attribute = $obj->findAttribute($tableField["Field"])) !== null && strtolower($tableField["Field"]) != strtolower($idField)) {
                if($attribute->getValue() !== null) {
                    array_push($fieldsToInclude, $tableField["Field"]);
                    $terms[] = $attribute->getValue();
                    $qs[] = '?';
                }
            }
        }
        $sql .= implode(",", $fieldsToInclude);
        $sql .= ") VALUES (";
        $sql .= implode(",", $qs);
        $sql .= ")";

        return array($sql, $terms);
    }

    /**
     * @param DomainObject $obj
     * @return array
     */
    private function createUpdateSQL(DomainObject $obj) {
        $sql = "UPDATE `" . $this->dataMap->getTableName() . "` SET ";
        $fieldsToInclude = array();
        foreach($this->_savedObjectColumns[$this->dataMap->getDomainClassName()] as $tableField) {
            if(($attribute = $obj->findAttribute($tableField["Field"])) !== null) {
                if($attribute->getChanged() === true) {
                    array_push($fieldsToInclude, $tableField["Field"]);
                    $terms[] = $attribute->getValue();
                    $qs[] = '?';
                }
            }
        }

        for($i = 0; $i < count($fieldsToInclude); ++$i) {
            if($i > 0) {
                $sql .= ", ";
            }
            $sql .= $fieldsToInclude[$i] . " = ?";
        }

        $idField = $this->dataMap->getIdField();
        $sql .= " WHERE ". $idField . " = ?";
        $idMethod = "get".ucfirst($idField);
        $terms[] = $obj->$idMethod();

        return array($sql, $terms);
    }

    public function delete(DomainObject $obj) {
        // TODO: This should work, but untested
        $idField = $this->dataMap->getIdField();
        $sql = "DELETE FROM " . $this->dataMap->getTableName() . " WHERE " . $idField . " = ?";
        $idMethod = "get".ucfirst($idField);
        $preparedValues = array();
        array_push($preparedValues, $obj->$idMethod());

        if(!isset($this->statements[$sql])) {
            $this->statements[$sql] = self::$PDO->prepare($sql);
        }
        $stmt = $this->statements[$sql];
        $stmt->execute($preparedValues);
    }

    /**
     * @param array $whereClauseArray
     * @param \base\UnitOfWork $uow
     * @return Collection
     */
    public function findObjectsWhere($whereClauseArray, UnitOfWork $uow) {
        list($specificFields, $whereClause, $preparedValues) = $whereClauseArray;

        if($this->_joinName !== null) {
            $tableNames = $this->dataMap->getTableName() .", ".$this->dataMap->getJoinTable($this->_joinName);
            $sql = "SELECT " . $this->generateColumnList($specificFields) . " FROM " . $tableNames . $whereClause;
            $this->_joinName = null;// reset join name after using it because line 48 in Registry makes it so that a Mapper will be reused for Domain Objects of the same type
        } else {
            $sql = "SELECT " . $this->generateColumnList($specificFields) . " FROM " . $this->dataMap->getTableName() . $whereClause;
        }
//        syslog(LOG_INFO, $sql);
//        echo "<br><br>$sql<br><br>";

        if(!isset($this->statements[$sql])) {
            $this->statements[$sql] = self::$PDO->prepare($sql);
        }
        $stmt = $this->statements[$sql];
        $stmt->execute($preparedValues);
        $rs = $stmt->fetchAll();

        return $this->loadAll($rs, $uow);
    }

    /**
     * @param array $whereClauseArray
     * @param \base\UnitOfWork $uow
     * @return DomainObject
     */
    public function findOne($whereClauseArray, UnitOfWork $uow) {
        list($specificFields, $whereClause, $preparedValues) = $whereClauseArray;

        if($this->_joinName !== null) {
            $tableNames = $this->dataMap->getTableName() .", ".$this->dataMap->getJoinTable($this->_joinName);
            $sql = "SELECT " . $this->generateColumnList($specificFields) . " FROM " . $tableNames . $whereClause;
            $this->_joinName = null;// reset join name after using it because line 48 in Registry makes it so that a Mapper will be reused for Domain Objects of the same type
        } else {
            $sql = "SELECT " . $this->generateColumnList($specificFields) . " FROM " . $this->dataMap->getTableName() . $whereClause;
        }
//        echo "<br><br>$sql";print_r($preparedValues);echo"<br><br>";
        if(!isset($this->statements[$sql])) {
            $this->statements[$sql] = self::$PDO->prepare($sql);
        }
        $stmt = $this->statements[$sql];
        $stmt->execute($preparedValues);
        $rs = $stmt->fetchAll();

        return $this->load($rs[0], $uow);
    }

    /**
     * @param string $id
     * @param \base\UnitOfWork $uow
     * @return DomainObject
     */
    public function findObjectById($id, UnitOfWork $uow) {
        if ($uow->isLoaded("\domain\\".$this->dataMap->getDomainClassName(), $id)) {
            return $uow->getObject("\domain\\".$this->dataMap->getDomainClassName(), $id);
        }

        $whereClause = " WHERE ".$this->dataMap->getIdField() ." = ?";
        $whereClauseArray = array(null, $whereClause, array($id));

        return $this->findOne($whereClauseArray, $uow);
    }

    /**
     * @param array $resultSet
     * @param UnitOfWork $uow
     * @return Collection
     */
    public function loadAll(array $resultSet, UnitOfWork $uow) {
        $collection = new Collection();
        foreach($resultSet as $rs) {
            $domainObject = $this->load($rs, $uow);

            $collection->add($domainObject);
        }
        return $collection;
    }

    /**
     * @param array $rs
     * @param UnitOfWork $uow
     * @return \domain\DomainObject|null
     */
    public function load(array $rs = null, UnitOfWork $uow)  {
        if($rs === null) return null;// no matches

        $id = $rs[$this->dataMap->getIdField()];
        if ($uow->isLoaded("\domain\\".$this->dataMap->getDomainClassName(), $id)) {
            $result = $uow->getObject("\domain\\".$this->dataMap->getDomainClassName(), $id);
            $this->attachExtras($rs, $result);
            return $result;
        }

        $result = $this->dataMap->getDomainClassReflectionClass()->newInstanceArgs(array(false));

        $this->loadAttributes($rs, $result);
        $uow->registerClean($result);
        return $result;
    }

    protected function attachExtras($rs, $obj) {
        // this almost always does nothing
        // it's used if we need to attach a value onto a Domain Object that comes from a Join but only does anything in the case where we
        // had created the same object already but now the value that came from the Join is different
    }

    /**
     * Loads values onto a DomainObject's Attributes based on the passed in result set
     * @access protected
     * @param $rs
     * @param \domain\DomainObject $domainObject
     */
    protected function loadAttributes(array $rs, DomainObject $domainObject) {
        foreach($rs as $resultField => $val) {
            if(($attribute = $domainObject->findAttribute($resultField)) !== null) {
                $attribute->setValue($val);
            }
        }
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

    abstract function loadDataMap();
}
?>