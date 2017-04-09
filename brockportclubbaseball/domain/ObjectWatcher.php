<?php
/**
 * Created by PhpStorm.
 * User: Justin
 * Date: 7/19/14
 * Time: 2:23 PM
 */

namespace domain;


class ObjectWatcher {
    private $all = array();

    public function __construct(){

    }

    private function globalKey(DomainObject $obj, $idField) {
        $idMethod = "get".ucfirst($idField);
        $key = "\\". get_class($obj). "." . $obj->$idMethod();
        return $key;
    }

    public function add(DomainObject $obj, $idField) {
        $this->all[$this->globalKey($obj, $idField)] = $obj;
    }

    public function remove(DomainObject $obj, $idField) {
        if(isset($this->all[$this->globalKey($obj, $idField)])) {
            unset($this->all[$this->globalKey($obj, $idField)]);
        }
    }

    /**
     * Check if an object is already in the IdentityMap
     * @param string $classname
     * @param string $id
     * @return bool
     */
    public function exists($classname, $id) {
        $key = "$classname.$id";
        if(isset($this->all[$key])) {
            return true;
        }
        return false;
    }

    /**
     * Returns an object from the map
     * @param $classname
     * @param $id
     * @return DomainObject
     */
    public function getObject($classname, $id) {
        if($this->exists($classname, $id)) {
            return $this->all["$classname.$id"];
        }
        return null;
    }

    public function clearAll() {
        $this->all = array();
    }
}
?>