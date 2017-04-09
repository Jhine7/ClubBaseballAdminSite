<?php
/**
 * Created by PhpStorm.
 * User: Justin
 * Date: 7/19/14
 * Time: 2:33 PM
 */

namespace domain;


use base\Registry;
use domain\base\BaseAdmin;

class Admin extends BaseAdmin {

    public static function findAdminById($adminId) {
        return Registry::adminRepository()->findObjectById($adminId, "Admin");
    }

    public static function findAdminByNameAndPassword($name, $password) {
        return Registry::adminRepository()->findAdminByNameAndPassword($name, $password);
    }
}
?>