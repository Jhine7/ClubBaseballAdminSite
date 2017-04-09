<?php
/**
 * Created by PhpStorm.
 * User: Justin
 * Date: 4/12/14
 * Time: 2:32 PM
 */

namespace base;


use domain\DomainObject;

class UnitOfWork {
    private $_newObjects = array();
    private $_dirtyObjects = array();
    private $_removedObjects = array();
    private $_identityMap;

    function __construct() {
        $this->_identityMap = new \domain\ObjectWatcher();
    }

    /**
     * Gets Database Mapper object specified by $klass
     * @access public
     * @param string $klass
     * @return \mapper\Mapper
     */
    public function getMapper($klass) {
        return Registry::getMapper($klass);
    }

    /**
     * Calls helper methods to locate the appropriate mapping methods for all of the stored objects and perform any needed operations
     * @access public
     */
    public function commit() {
        $result = true;
//        $result = $this->insertNew();
        $this->updateDirty();// TODO: Will need to update these
        $this->deleteRemoved();// TODO: Will need to update these

        return $result;
    }

    /**
     * Inserts all Objects created during execution of the command and updates the keys table to stay consistent with the Object ids
     * @access private
     */
    private function insertNew() {
        $objectTypesInsertedIds = array();
        $result = true;
        foreach($this->_newObjects as $newObject) {
            $mapper = $this->getMapper(str_replace("domain\\", "", get_class($newObject)));
            $result = $mapper->insert($newObject);

            if($result === true) {
                $idField = $mapper->getDomainObjectIdField();
                $idMethod = "get".ucfirst($idField);
                if(isset($objectTypesInsertedIds[get_class($newObject)])) {
                    if($newObject->$idMethod() > $objectTypesInsertedIds[get_class($newObject)]) {
                        $objectTypesInsertedIds[get_class($newObject)] = $newObject->$idMethod();
                    }
                } else {
                    $objectTypesInsertedIds[get_class($newObject)] = $newObject->$idMethod();
                }
            }
        }

//        if($result === true) {
//            $pdo = ApplicationRegistry::getDSN();
//            foreach($objectTypesInsertedIds as $objectType => $idValue) {
//                $newMapper = $this->getMapper(str_replace("domain\\", "", $objectType));
//                $sql = "UPDATE `tablekeys` SET nextId = ? WHERE name =  '" . $newMapper->getDomainObjectTableName() . "'";
//                $terms = array();
//                $terms[] = $idValue + 1;
//                $stmt = $pdo->prepare($sql);
//                $stmt->execute($terms);
//            }
//        }

        return $result;
    }

    /**
     * @access private
     */
    private function updateDirty() {
        foreach($this->_dirtyObjects as $dirtyObject) {
            $this->getMapper(str_replace("domain\\", "", get_class($dirtyObject)))->update($dirtyObject);
        }
    }

    /**
     * @access private
     */
    private function deleteRemoved() {
        foreach($this->_removedObjects as $removedObject) {
            $this->getMapper(str_replace("domain\\", "", get_class($removedObject)))->delete($removedObject);
        }
    }

    /**
     * New means this object does not exist in the database yet (results from instantiating a new DomainObject explicitly)
     * @access public
     * @param \domain\DomainObject $domainObject
     * @throws \Exception
     */
    public function registerNew(\domain\DomainObject $domainObject) {
//        $idField = $this->getMapper(str_replace("domain\\", "", get_class($domainObject)))->getDomainObjectIdField();
//        $idMethod = "get".ucfirst($idField);
//        if($domainObject->$idMethod() !== null) {
//            throw new \Exception("Error: Unit of Work cannot register an object as new if the object already has an id");
//        }
//
////        $this->assignNewPrimaryKey($domainObject, $idField);// gives us an appropriate id for object w/o having to insert to get it (UoW does all inserts at end)
//
//        if($this->_newObjects[get_class($domainObject).$domainObject->$idMethod()] !== null) {
//            throw new \Exception("Error: Trying to register a DomainObject as new in Unit of Work when it has already been registered as new");
//        }
//        if($this->_dirtyObjects[get_class($domainObject).$domainObject->$idMethod()] !== null) {
//            throw new \Exception("Error: Trying to register a DomainObject as new in Unit of Work when it has already been registered as dirty");
//        }
//        if($this->_removedObjects[get_class($domainObject).$domainObject->$idMethod()] !== null) {
//            throw new \Exception("Error: Trying to register a DomainObject as new in Unit of Work when it has already been registered as removed");
//        }
//
//        $this->_newObjects[get_class($domainObject).$domainObject->$idMethod()] = $domainObject;
//        $this->_identityMap->add($domainObject, $idField);
    }

    /**
     * Sets the id of the passed in DomainObject based on what the next id for the DomainObject type should be
     * @access private
     * @param DomainObject $domainObject
     * @param $idField
     */
    private function assignNewPrimaryKey(DomainObject $domainObject, $idField) {
        $tableName = $this->getMapper(str_replace("domain\\", "", get_class($domainObject)))->getDomainObjectTableName();

        $newKeys = array_reverse(array_keys($this->_newObjects));// loop keys backwards

        $domainObjectClass = get_class($domainObject);
        foreach($newKeys as $newKey) {
            if (strpos($newKey, $domainObjectClass) !== false) {
                $latestKey = $newKey;
                break;
            }
        }

        $pdo = ApplicationRegistry::getDSN();
        $sql = "SELECT nextId FROM `tablekeys` WHERE name =  ? ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array($tableName));
        $rs = $stmt->fetchAll();
//            echo $tableName;
        $domainObject->findAttribute($idField)->setValue($rs[0]["nextId"]);

        // Important: Calling this as soon as we get the nextId
        $sql = "UPDATE `tablekeys` SET nextId = nextId + 1 WHERE name =  '" . $tableName . "'";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
    }

    /**
     * Dirty means it exists in the database and we've made changes to it since loading (results from any set method of a Base DomainObject Class)
     * @access public
     * @param \domain\DomainObject $domainObject
     * @throws \Exception
     */
    public function registerDirty(\domain\DomainObject $domainObject) {
        $idField = $this->getMapper(str_replace("domain\\", "", get_class($domainObject)))->getDomainObjectIdField();
        $idMethod = "get".ucfirst($idField);
        if($domainObject->$idMethod() === null) {
            throw new \Exception("Error: Unit of Work cannot register an object as dirty if the id of that object is null");
        }
        if($this->_removedObjects[get_class($domainObject).$domainObject->$idMethod()] !== null) {
            throw new \Exception("Error: Trying to register a DomainObject as dirty in Unit of Work when it has already been registered as removed");
        }

        if($this->_dirtyObjects[get_class($domainObject).$domainObject->$idMethod()] === null &&
            $this->_newObjects[get_class($domainObject).$domainObject->$idMethod()] === null) {
            $this->_dirtyObjects[get_class($domainObject).$domainObject->$idMethod()] = $domainObject;
        }
    }

    /**
     * Clean means it exists in the database but haven't made any changes to it (results from loading Objects with a mapper)
     * @access public
     * @param \domain\DomainObject $domainObject
     * @throws \Exception
     */
    public function registerClean(\domain\DomainObject $domainObject) {
        $idField = $this->getMapper(str_replace("domain\\", "", get_class($domainObject)))->getDomainObjectIdField();
        $idMethod = "get".ucfirst($idField);
        if($domainObject->$idMethod() === null) {
            throw new \Exception("Error: Unit of Work cannot register an object as clean if the id of that object is null");
        }

        $this->_identityMap->add($domainObject, $idField);
    }

    /**
     * @access public
     * @param \domain\DomainObject $domainObject
     * @throws \Exception
     */
    public function registerRemoved(\domain\DomainObject $domainObject) {
        $idField = $this->getMapper(str_replace("domain\\", "", get_class($domainObject)))->getDomainObjectIdField();
        $idMethod = "get".ucfirst($idField);
        if($domainObject->$idMethod() === null) {
            throw new \Exception("Error: Unit of Work cannot register an object as removed if the id of that object is null");
        }
        if($this->_newObjects[get_class($domainObject).$domainObject->$idMethod()] !== null) {
            unset($this->_newObjects[get_class($domainObject).$domainObject->$idMethod()]);// new and hasn't been created yet, so never let it get the chance to
            return;
        }

        unset($this->_dirtyObjects[get_class($domainObject).$domainObject->$idMethod()]);

        if($this->_removedObjects[get_class($domainObject).$domainObject->$idMethod()] === null) {
            $this->_removedObjects[get_class($domainObject).$domainObject->$idMethod()] = $domainObject;
        }

        $this->_identityMap->remove($domainObject, $idField);
    }

    /**
     * Check if an Object already exists in the session
     * @access public
     * @param string $className
     * @param string $id
     * @return bool
     */
    public function isLoaded($className, $id) {
        return $this->_identityMap->exists($className, $id);
    }

    /**
     * Gets the Object existing in the current session
     * @access public
     * @param string $className
     * @param string $id
     * @return \domain\DomainObject
     */
    public function getObject($className, $id) {
        return $this->_identityMap->getObject($className, $id);
    }
}
?>