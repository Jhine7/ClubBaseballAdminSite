<?php
/**
 * Created by PhpStorm.
 * User: Justin
 * Date: 7/19/14
 * Time: 2:28 PM
 */

namespace mapper;


class AdminMapper extends Mapper {
public function loadDataMap() {
    // This is an auto generated method. Do not edit.
    $this->dataMap = new DataMap("Admin", "admins", "adminId");
}
}
?>