<?php
/**
 * Created by PhpStorm.
 * User: Justin
 * Date: 7/19/14
 * Time: 2:39 PM
 */

namespace domain;


use base\Registry;
use domain\base\BasePlayer;

class Player extends BasePlayer {

    /**
     * @param $playerId
     * @return Game
     */
    public static function findPlayerById($playerId) {
        return Registry::playerRepository()->findObjectById($playerId, "Player");
    }

    public static function findAllPlayers() {
        return Registry::playerRepository()->findAllPlayers();
    }

    public static function getFullNameFromPlayerId($playerId) {
        return Registry::playerRepository()->getFullNameFromPlayerId($playerId);
    }
}
?>