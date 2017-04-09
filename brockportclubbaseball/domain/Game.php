<?php
/**
 * Created by PhpStorm.
 * User: Justin
 * Date: 7/19/14
 * Time: 2:39 PM
 */

namespace domain;


use base\Registry;
use domain\base\BaseGame;

class Game extends BaseGame {

    /**
     * @param $userId
     * @return Game
     */
    public static function findGameById($userId) {
        return Registry::gameRepository()->findObjectById($userId, "Game");
    }

    public static function pitcherExistsForGame($playerId, $gameId) {
        return Registry::gameRepository()->pitcherExistsForGame($playerId, $gameId);
    }

    public static function hitterExistsForGame($playerId, $gameId) {
        return Registry::gameRepository()->hitterExistsForGame($playerId, $gameId);
    }

    public static function getSeasonPitchersArray($fallYear, $springYear) {
        return Registry::gameRepository()->getSeasonPitchersArray($fallYear, $springYear);
    }

    public static function getCareerPitchersArray() {
        return Registry::gameRepository()->getCareerPitchersArray();
    }

    public static function getSeasonHittersArray($fallYear, $springYear) {
        return Registry::gameRepository()->getSeasonHittersArray($fallYear, $springYear);
    }

    public static function getCareerHittersArray() {
        return Registry::gameRepository()->getCareerHittersArray();
    }

    public static function getNumGamesPlayedForSeason($fallYear, $springYear) {
        return Registry::gameRepository()->getNumGamesPlayedForSeason($fallYear, $springYear);
    }

    public static function findAllGames() {
        return Registry::gameRepository()->findAllGames();
    }

    public function getPitchersArray() {
        return Registry::gameRepository()->getPitchersArray($this);
    }

    public function getAllPitcherIds() {
        return Registry::gameRepository()->getAllPitcherIds($this);
    }

    public function getHittersArray() {
        return Registry::gameRepository()->getHittersArray($this);
    }

    public function getAllHitterIds() {
        return Registry::gameRepository()->getAllHitterIds($this);
    }

    public function updatePitcherForGame($playerId, $wins, $losses, $games, $gamesStarted, $completeGames, $saves, $saveOpportunities, $inningsPitched, $hits,
                        $runs, $earnedRuns, $homeRuns, $walks, $strikeOuts, $shutOuts, $hitByPitch) {
        Registry::gameRepository()->updatePitcherForGame($this, $playerId, $wins, $losses, $games, $gamesStarted, $completeGames, $saves, $saveOpportunities, $inningsPitched, $hits,
            $runs, $earnedRuns, $homeRuns, $walks, $strikeOuts, $shutOuts, $hitByPitch);
    }

    public function updateHitterForGame($playerId, $games, $atBats, $runs, $hits, $doubles, $triples, $homeRuns, $rbis, $walks,
                                        $strikeOuts, $stolenBases, $caughtStealing, $intentionalWalks, $hitByPitch, $sacBunts, $sacFlys) {
        Registry::gameRepository()->updateHitterForGame($this, $playerId, $games, $atBats, $runs, $hits, $doubles, $triples, $homeRuns, $rbis, $walks,
            $strikeOuts, $stolenBases, $caughtStealing, $intentionalWalks, $hitByPitch, $sacBunts, $sacFlys);
    }

    public function addPitcherForGame($playerId, $wins, $losses, $games, $gamesStarted, $completeGames, $saves, $saveOpportunities, $inningsPitched, $hits,
                                         $runs, $earnedRuns, $homeRuns, $walks, $strikeOuts, $shutOuts, $hitByPitch) {
        Registry::gameRepository()->addPitcherForGame($this, $playerId, $wins, $losses, $games, $gamesStarted, $completeGames, $saves, $saveOpportunities, $inningsPitched, $hits,
            $runs, $earnedRuns, $homeRuns, $walks, $strikeOuts, $shutOuts, $hitByPitch);
    }

    public function addHitterForGame($playerId, $games, $atBats, $runs, $hits, $doubles, $triples, $homeRuns, $rbis, $walks,
                                     $strikeOuts, $stolenBases, $caughtStealing, $intentionalWalks, $hitByPitch, $sacBunts, $sacFlys) {
        Registry::gameRepository()->addHitterForGame($this, $playerId, $games, $atBats, $runs, $hits, $doubles, $triples, $homeRuns, $rbis, $walks,
            $strikeOuts, $stolenBases, $caughtStealing, $intentionalWalks, $hitByPitch, $sacBunts, $sacFlys);
    }

    public function deletePitcher($pitcherId) {
        Registry::gameRepository()->deletePitcher($this, $pitcherId);
    }

    public function deleteHitter($pitcherId) {
        Registry::gameRepository()->deleteHitter($this, $pitcherId);
    }
}
?>