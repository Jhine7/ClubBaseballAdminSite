<?php
/**
 * Created by PhpStorm.
 * User: Justin
 * Date: 7/24/14
 * Time: 9:41 PM
 */

namespace domain;


use mapper\Criteria;

class AdminRepository extends Repository {
    private $_className = "Admin";

    /**
     * @param $name
     * @param $password
     * @return Admin
     */
    public function findAdminByNameAndPassword($name, $password) {
        $allCriteria = array();
        $allCriteria[] = Criteria::equals(Admin::ADMINNAME, $name);
        $allCriteria[] = Criteria::equals(Admin::ADMINPASSWORD, $password);
        return $this->soleMatch($allCriteria, $this->_className);
    }
}
?>