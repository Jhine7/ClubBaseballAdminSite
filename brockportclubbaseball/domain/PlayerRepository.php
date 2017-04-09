<?php
/**
 * Created by PhpStorm.
 * User: Justin
 * Date: 7/20/14
 * Time: 12:04 AM
 */

namespace domain;


use base\Registry;
use mapper\Criteria;
use mapper\QueryObject;

class PlayerRepository extends Repository {
    private $_className = "Player";

    /**
     * @return \mapper\Collection
     */
    public function findAllPlayers() {
        $query = new QueryObject($this->_className);
        $query->setEndingConditions("ORDER BY " .Player::PLAYERFIRSTNAME . " ASC");
        $criteria =  Criteria::greaterThan(Player::PLAYERID, 0);
        $query->addCriteria($criteria);
        return $query->execute(Registry::getUnitOfWork());
    }

    /**
     * @param $playerId
     * @return string
     */
    public function getFullNameFromPlayerId($playerId) {
        $query = new QueryObject($this->_className, array("playerFirstName, playerLastName"));
        $criteria =  Criteria::equals(Player::PLAYERID, $playerId);
        $query->addCriteria($criteria);
        $results = $query->findDataOnly(Registry::getUnitOfWork());
        return $results[0]['playerFirstName']." ".$results[0]['playerLastName'];
    }
}
?>