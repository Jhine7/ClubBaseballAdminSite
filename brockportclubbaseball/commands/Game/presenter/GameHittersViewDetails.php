<?php
/**
 * Created by PhpStorm.
 * User: Justin
 * Date: 7/19/14
 * Time: 10:37 PM
 */

namespace commands\Game\presenter;


use domain\Game;
use mapper\Collection;

class GameHittersViewDetails {
    private $_game;
    private $_players;
    private $_hitters;

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
     * @param array $hitters
     */
    public function setHitters(array $hitters) {
        $this->_hitters = $hitters;
    }

    /**
     * @return array
     */
    public function getHitters() {
        return $this->_hitters;
    }
}
?>