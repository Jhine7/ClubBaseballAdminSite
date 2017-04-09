<?php
/**
 * Created by PhpStorm.
 * User: Justin
 * Date: 7/19/14
 * Time: 8:36 PM
 */

namespace domain;


use base\ApplicationRegistry;
use base\Registry;
use mapper\Criteria;
use mapper\QueryObject;

class GameRepository extends Repository {
    private $_className = "Game";

    public function findAllGames() {
        $query = new QueryObject($this->_className);
        $query->setEndingConditions("ORDER BY " .Game::YEAR . " DESC, ".Game::MONTH . " DESC, " . Game::DAY . " DESC");
        $criteria =  Criteria::greaterThan(Game::GAMEID, 0);
        $query->addCriteria($criteria);
        return $query->execute(Registry::getUnitOfWork());
    }

    /**
     * @param Game $game
     * @return array
     */
    public function getPitchersArray(Game $game) {
        $sql = "SELECT * FROM gamePitchers WHERE gameId = :gameId";
        $pdo = ApplicationRegistry::getDSN();
        $stmt = $pdo->prepare($sql);
        $params = array(
            'gameId' => $game->getGameId()
        );
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /**
     * @param Game $game
     * @return array
     */
    public function getAllPitcherIds(Game $game) {
        $sql = "SELECT playerId FROM gamePitchers WHERE gameId = :gameId";
        $pdo = ApplicationRegistry::getDSN();
        $stmt = $pdo->prepare($sql);
        $params = array(
            'gameId' => $game->getGameId()
        );
        $stmt->execute($params);
        $results = $stmt->fetchAll();

        $ids = array();
        foreach($results as $result) {
            array_push($ids, $result['playerId']);
        }

        return $ids;
    }

    /**
     * @param $playerId
     * @param $gameId
     * @return bool
     */
    public function pitcherExistsForGame($playerId, $gameId) {
        $sql = "SELECT COUNT(*) as theCount FROM gamePitchers WHERE playerId = :playerId AND gameId = :gameId";
        $pdo = ApplicationRegistry::getDSN();
        $stmt = $pdo->prepare($sql);
        $params = array(
            'playerId' => $playerId,
            'gameId' => $gameId
        );
        $stmt->execute($params);
        $stmt->bindColumn('theCount', $exits);
        $stmt->fetch(\PDO::FETCH_BOUND);

        return $exits > 0;
    }

    public function updatePitcherForGame(Game $game, $playerId, $wins, $losses, $games, $gamesStarted, $completeGames, $saves, $saveOpportunities, $inningsPitched, $hits,
                                         $runs, $earnedRuns, $homeRuns, $walks, $strikeOuts, $shutOuts, $hitByPitch) {
        $sql = "UPDATE gamePitchers SET won = :wins, loss = :losses, g = :games, gs = :gamesStarted, cg = :completeGames, sv = :saves, svo = :saveOpportunities, ip = :inningsPitched,
                                h = :hits, r = :runs, er = :earnedRuns, hr = :homeRuns, bb = :walks, so = :strikeOuts, sho = :shutOuts, hbp = :hitByPitch WHERE gameId = :gameId
                                AND playerId = :playerId";
        $pdo = ApplicationRegistry::getDSN();
        $stmt = $pdo->prepare($sql);
        $params = array(
            'wins' => $wins,
            'losses' => $losses,
            'games' => $games,
            'gamesStarted' => $gamesStarted,
            'completeGames' => $completeGames,
            'saves' => $saves,
            'saveOpportunities' => $saveOpportunities,
            'inningsPitched' => $inningsPitched,
            'hits' => $hits,
            'runs' => $runs,
            'earnedRuns' => $earnedRuns,
            'homeRuns' => $homeRuns,
            'walks' => $walks,
            'strikeOuts' => $strikeOuts,
            'shutOuts' => $shutOuts,
            'hitByPitch' => $hitByPitch,
            'gameId' => $game->getGameId(),
            'playerId' => $playerId
        );
       $stmt->execute($params);
    }

    public function addPitcherForGame(Game $game, $playerId, $wins, $losses, $games, $gamesStarted, $completeGames, $saves, $saveOpportunities, $inningsPitched, $hits,
                                      $runs, $earnedRuns, $homeRuns, $walks, $strikeOuts, $shutOuts, $hitByPitch) {
        $sql = "INSERT INTO gamePitchers (won, loss, g, gs, cg, sv, svo, ip, h, r, er, hr, bb, so, sho, hbp, playerId, gameId) VALUES (:wins, :losses, :games, :gamesStarted, :completeGames,
                              :saves, :saveOpportunities, :inningsPitched, :hits, :runs, :earnedRuns, :homeRuns, :walks, :strikeOuts, :shutOuts, :hitByPitch, :playerId, :gameId)";
        $pdo = ApplicationRegistry::getDSN();
        $stmt = $pdo->prepare($sql);
        $params = array(
            'wins' => $wins,
            'losses' => $losses,
            'games' => $games,
            'gamesStarted' => $gamesStarted,
            'completeGames' => $completeGames,
            'saves' => $saves,
            'saveOpportunities' => $saveOpportunities,
            'inningsPitched' => $inningsPitched,
            'hits' => $hits,
            'runs' => $runs,
            'earnedRuns' => $earnedRuns,
            'homeRuns' => $homeRuns,
            'walks' => $walks,
            'strikeOuts' => $strikeOuts,
            'shutOuts' => $shutOuts,
            'hitByPitch' => $hitByPitch,
            'gameId' => $game->getGameId(),
            'playerId' => $playerId
        );
        $stmt->execute($params);
    }

    public function deletePitcher(Game $game, $pitcherId) {
        $sql = "DELETE FROM gamePitchers WHERE gameId = :gameId && playerId = :playerId";
        $pdo = ApplicationRegistry::getDSN();
        $stmt = $pdo->prepare($sql);
        $params = array(
            'gameId' => $game->getGameId(),
            'playerId' => $pitcherId
        );

       $stmt->execute($params);
    }


    /**
     * @param Game $game
     * @return array
     */
    public function getHittersArray(Game $game) {
        $sql = "SELECT * FROM gameHitters WHERE gameId = :gameId";
        $pdo = ApplicationRegistry::getDSN();
        $stmt = $pdo->prepare($sql);
        $params = array(
            'gameId' => $game->getGameId()
        );
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /**
     * @param Game $game
     * @return array
     */
    public function getAllHitterIds(Game $game) {
        $sql = "SELECT playerId FROM gameHitters WHERE gameId = :gameId";
        $pdo = ApplicationRegistry::getDSN();
        $stmt = $pdo->prepare($sql);
        $params = array(
            'gameId' => $game->getGameId()
        );
        $stmt->execute($params);
        $results = $stmt->fetchAll();

        $ids = array();
        foreach($results as $result) {
            array_push($ids, $result['playerId']);
        }

        return $ids;
    }

    /**
     * @param $playerId
     * @param $gameId
     * @return bool
     */
    public function hitterExistsForGame($playerId, $gameId) {
        $sql = "SELECT COUNT(*) as theCount FROM gameHitters WHERE playerId = :playerId AND gameId = :gameId";
        $pdo = ApplicationRegistry::getDSN();
        $stmt = $pdo->prepare($sql);
        $params = array(
            'playerId' => $playerId,
            'gameId' => $gameId
        );
        $stmt->execute($params);
        $stmt->bindColumn('theCount', $exits);
        $stmt->fetch(\PDO::FETCH_BOUND);

        return $exits > 0;
    }

    public function updateHitterForGame(Game $game, $playerId, $games, $atBats, $runs, $hits, $doubles, $triples, $homeRuns, $rbis, $walks,
                                        $strikeOuts, $stolenBases, $caughtStealing, $intentionalWalks, $hitByPitch, $sacBunts, $sacFlys) {
        $sql = "UPDATE gameHitters SET g = :games, ab = :atBats, r = :runs, h = :hits, 2b = :doubles, 3b = :triples, hr = :homeRuns, rbi = :rbis,
                                bb = :walks, so = :strikeOuts, sb = :stolenBases, cs = :caughtStealing, ibb = :intentionalWalks, hbp = :hitByPitch, sacb = :sacBunts, sacf = :sacFlys WHERE gameId = :gameId
                                AND playerId = :playerId";
        $pdo = ApplicationRegistry::getDSN();
        $stmt = $pdo->prepare($sql);
        $params = array(
            'games' => $games,
            'atBats' => $atBats,
            'runs' => $runs,
            'hits' => $hits,
            'doubles' => $doubles,
            'triples' => $triples,
            'homeRuns' => $homeRuns,
            'rbis' => $rbis,
            'walks' => $walks,
            'strikeOuts' => $strikeOuts,
            'stolenBases' => $stolenBases,
            'caughtStealing' => $caughtStealing,
            'intentionalWalks' => $intentionalWalks,
            'hitByPitch' => $hitByPitch,
            'sacBunts' => $sacBunts,
            'sacFlys' => $sacFlys,
            'gameId' => $game->getGameId(),
            'playerId' => $playerId
        );
        $stmt->execute($params);
    }

    public function addHitterForGame(Game $game, $playerId, $games, $atBats, $runs, $hits, $doubles, $triples, $homeRuns, $rbis, $walks,
                                     $strikeOuts, $stolenBases, $caughtStealing, $intentionalWalks, $hitByPitch, $sacBunts, $sacFlys) {
        $sql = "INSERT INTO gameHitters (g, ab, r, h, 2b, 3b, hr, rbi, bb, so, sb, cs, ibb, hbp, sacb, sacf, playerId, gameId) VALUES (:games, :atBats, :runs, :hits, :doubles,
                              :triples, :homeRuns, :rbis, :walks, :strikeOuts, :stolenBases, :caughtStealing, :intentionalWalks, :hitByPitch, :sacBunts, :sacFlys, :playerId, :gameId)";
        $pdo = ApplicationRegistry::getDSN();
        $stmt = $pdo->prepare($sql);
        $params = array(
            'games' => $games,
            'atBats' => $atBats,
            'runs' => $runs,
            'hits' => $hits,
            'doubles' => $doubles,
            'triples' => $triples,
            'homeRuns' => $homeRuns,
            'rbis' => $rbis,
            'walks' => $walks,
            'strikeOuts' => $strikeOuts,
            'stolenBases' => $stolenBases,
            'caughtStealing' => $caughtStealing,
            'intentionalWalks' => $intentionalWalks,
            'hitByPitch' => $hitByPitch,
            'sacBunts' => $sacBunts,
            'sacFlys' => $sacFlys,
            'gameId' => $game->getGameId(),
            'playerId' => $playerId
        );
        $stmt->execute($params);
    }

    public function deleteHitter(Game $game, $pitcherId) {
        $sql = "DELETE FROM gameHitters WHERE gameId = :gameId && playerId = :playerId";
        $pdo = ApplicationRegistry::getDSN();
        $stmt = $pdo->prepare($sql);
        $params = array(
            'gameId' => $game->getGameId(),
            'playerId' => $pitcherId
        );

        $stmt->execute($params);
    }

    /**
     * @param $fallYear
     * @param $springYear
     * @return array
     */
    public function getSeasonPitchersArray($fallYear, $springYear) {
        $sql = "SELECT * FROM game, gamePitchers, players WHERE ((game.year = :fallYear AND game.month >= 9) OR (game.year = :springYear AND game.month <= 5)) AND
                    game.gameId = gamePitchers.gameId AND gamePitchers.playerId = players.playerId ORDER by players.playerLastName ASC";
        $pdo = ApplicationRegistry::getDSN();
        $stmt = $pdo->prepare($sql);
        $params = array(
            'fallYear' => $fallYear,
            'springYear' => $springYear
        );
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /**
     * @return array
     */
    public function getCareerPitchersArray() {
        $sql = "SELECT * FROM game, gamePitchers, players WHERE
                    game.gameId = gamePitchers.gameId AND gamePitchers.playerId = players.playerId ORDER by players.playerLastName ASC";
        $pdo = ApplicationRegistry::getDSN();
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * @param $fallYear
     * @param $springYear
     * @return array
     */
    public function getSeasonHittersArray($fallYear, $springYear) {
        $sql = "SELECT * FROM game, gameHitters, players WHERE ((game.year = :fallYear AND game.month >= 9) OR (game.year = :springYear AND game.month <= 5)) AND
                    game.gameId = gameHitters.gameId AND gameHitters.playerId = players.playerId ORDER by players.playerLastName ASC";
        $pdo = ApplicationRegistry::getDSN();
        $stmt = $pdo->prepare($sql);
        $params = array(
            'fallYear' => $fallYear,
            'springYear' => $springYear
        );
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function getNumGamesPlayedForSeason($fallYear, $springYear) {
        $sql = "select count(*) as gamesPlayed from game where ((game.year = :fallYear AND game.month >= 9) OR (game.year = :springYear AND game.month <= 5));";
        $pdo = ApplicationRegistry::getDSN();
        $stmt = $pdo->prepare($sql);
        $params = array(
            'fallYear' => $fallYear,
            'springYear' => $springYear
        );
        $stmt->execute($params);
        $result = $stmt->fetchAll();
        return $result[0]["gamesPlayed"];
    }

    /**
     * @return array
     */
    public function getCareerHittersArray() {
        $sql = "SELECT * FROM game, gameHitters, players WHERE
                    game.gameId = gameHitters.gameId AND gameHitters.playerId = players.playerId ORDER by players.playerLastName ASC";
        $pdo = ApplicationRegistry::getDSN();
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
?>