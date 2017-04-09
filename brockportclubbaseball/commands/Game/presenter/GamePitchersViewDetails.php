<?php
/**
 * Created by PhpStorm.
 * User: Justin
 * Date: 7/19/14
 * Time: 10:36 PM
 */

namespace commands\Game\presenter;


use domain\Game;
use mapper\Collection;

class GamePitchersViewDetails {
    private $_game;
    private $_players;
    private $_pitchers;

    /**
     * @param Game $game
     */
    public function setGame(Game $game) {
        $this->_game = $game;
    }

    /**
     * @return Game
     */
    public function getGame() {
        return $this->_game;
    }

    /**
     * @param Collection $players
     */
    public function setPlayers(Collection $players) {
        $this->_players = $players;
    }

    /**
     * @return Collection
     */
    public function getPlayers() {
        return $this->_players;
    }

    /**
     * @param array $pitchers
     */
    public function setPitchers(array $pitchers) {
        $this->_pitchers = $pitchers;
    }

    /**
     * @return array
     */
    public function getPitchers() {
        return $this->_pitchers;
    }
}
?>