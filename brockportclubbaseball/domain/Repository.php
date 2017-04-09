<?php
/**
 * Created by PhpStorm.
 * User: Justin
 * Date: 7/19/14
 * Time: 2:25 PM
 */

namespace domain;


use base\Registry;
use mapper\QueryObject;

abstract class Repository {
    private $_repositoryStrategy;

    /**
     * @access protected
     * @param array $criteria
     * @param $className
     * @return \mapper\Collection
     */
    protected function matching(array $criteria, $className) {
        if($this->_repositoryStrategy === null) {
            $this->_repositoryStrategy = new RelationalStrategy();
        }
        return $this->_repositoryStrategy->matching($criteria, $className);
    }

    /**
     * @access protected
     * @param array $criteria
     * @param string $className
     * @return DomainObject
     */
    protected function soleMatch(array $criteria, $className) {
        if($this->_repositoryStrategy === null) {
            $this->_repositoryStrategy = new RelationalStrategy();
        }
        return $this->_repositoryStrategy->soleMatch($criteria, $className);
    }

    /**
     * @access public
     * @param $id
     * @param $className
     * @return DomainObject
     */
    public function findObjectById($id, $className) {
        $query = new QueryObject($className);
        return $query->findObjectById(Registry::getUnitOfWork(), $id);
    }
}
?>