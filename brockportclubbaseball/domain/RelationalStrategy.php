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

class RelationalStrategy implements IRepositoryStrategy {
    function matching(array $criteria, $className) {
        $query = new QueryObject($className);
        foreach($criteria as $crit) {
            $query->addCriteria($crit);
        }
        return $query->execute(Registry::getUnitOfWork());
    }

    function soleMatch(array $criteria, $className) {
        $query = new QueryObject($className);
        foreach($criteria as $crit) {
            $query->addCriteria($crit);
        }
        return $query->findOne(Registry::getUnitOfWork());
    }
}
?>