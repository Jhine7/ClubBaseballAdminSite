<?php
/**
 * Created by PhpStorm.
 * User: Justin
 * Date: 7/19/14
 * Time: 9:54 PM
 */

namespace commands\Game\presenter;


use domain\Game;

class GameViewDetails {
    private $_game;

    /**
     * @param Game $game
     */
    public function setGame(Game $game = null) {
        $this->_game = $game;
    }

    /**
     * @return Game
     */
    public function getGame() {
        return $this->_game;
    }
}
?>