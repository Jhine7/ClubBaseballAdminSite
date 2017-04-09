<?php
/**
 * Created by PhpStorm.
 * User: Justin
 * Date: 7/19/14
 * Time: 8:28 PM
 */

namespace commands\Game\presenter;


use mapper\Collection;

class AllGamesViewDetails {
    private $_games;

    /**
     * @param Collection $games
     */
    public function setGames(Collection $games) {
        $this->_games = $games;
    }

    /**
     * @return Collection
     */
    public function getGames() {
        return $this->_games;
    }
}
?>