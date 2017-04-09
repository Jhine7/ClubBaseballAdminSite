<?php
/**
 * Created by PhpStorm.
 * User: Justin
 * Date: 7/19/14
 * Time: 2:09 PM
 */

namespace mapper;


use base\Registry;
use domain\DomainObject;

class Collection implements \Iterator {
    protected $total = 0;
    private $_pointer = 0;
    private $_objects = array();

    // TODO:: Could take an optional className in the constructor. If this is set it restricts any item being added to be that type.
    function __construct() {

    }

    public function length() {
        return $this->total;
    }

    private function getRow($num) {
        if($num >= $this->total || $num < 0) {
            return null;
        }

        if(isset($this->_objects[$num])) {
            return $this->_objects[$num];
        }
    }

    /**
     * Return the last item in the Collection
     * @return DomainObject
     */
    public function dequeue() {
        if(isset($this->_objects[$this->total - 1])) {
            return $this->_objects[$this->total - 1];
        }
    }

    /**
     * Return the first item in the Collection
     * @return DomainObject
     */
    public function pop() {
        if(isset($this->_objects[0])) {
            return $this->_objects[0];
        }
    }

    /**
     * Add a DomainObject to the Collection
     * @param DomainObject $object
     */
    function add(DomainObject $object) {
        $this->_objects[$this->total] = $object;
        $this->total++;
    }

    function addAt(DomainObject $object, $index) {
        $inserted = array( $object );
        array_splice( $this->_objects, $index, 0, $inserted ); // splice in at position $index
        $this->total++;
    }

    /**
     * Remove a DomainObject from the Collection
     * @param DomainObject $object
     */
    function remove(DomainObject $object) {
        $objectClass = get_class($object);
        $idField = Registry::getUnitOfWork()->getMapper(str_replace("domain\\", "", $objectClass))->getDomainObjectIdField();
        $idMethod = "get".$idField;

        $cnt = 0;
        $removed = false;
        foreach($this->_objects as $existingObject) {
            if(get_class($existingObject) === $objectClass) {
                if($existingObject->$idMethod() === $object->$idMethod()) {
                    $removed = true;
                    unset($this->_objects[$cnt]);
                    $this->_objects = array_values($this->_objects);
                    break;
                }
            }
            ++$cnt;
        }

        if($removed === true) $this->total--;
    }

    /**
     * @param array $sortArray
     */
    public function sort(array $sortArray)  {
        usort($this->_objects, $sortArray);
    }

    public function print_r() {
        print_r($this->_objects);
    }

    /**
     * @return array
     */
    public function toArray() {
        return $this->_objects;
    }

    public function rewind() {
        $this->_pointer = 0;
    }

    public function current() {
        return $this->getRow($this->_pointer);
    }

    public function key() {
        return $this->_pointer;
    }

    public function next() {
        $row = $this->getRow($this->_pointer);
        if($row) {$this->_pointer++;}
        return $row;
    }

    public function valid() {
        return (!is_null($this->current()));
    }
}
?>