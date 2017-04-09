<?php
/**
 * Created by PhpStorm.
 * User: Justin
 * Date: 7/19/14
 * Time: 2:37 PM
 */

namespace mapper;


class PlayerMapper extends Mapper {
public function loadDataMap() {
    // This is an auto generated method. Do not edit.
    $this->dataMap = new DataMap("Player", "players", "playerId");
}
}
?>