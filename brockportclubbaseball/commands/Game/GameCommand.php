<?php
/**
 * Created by PhpStorm.
 * User: Justin
 * Date: 7/19/14
 * Time: 7:35 PM
 */

namespace commands\Game;


use commands\common\Command;
use commands\Game\presenter\AddGamePresenter;
use commands\Game\presenter\AddGameViewDetails;
use commands\Game\presenter\AllGamesPresenter;
use commands\Game\presenter\AllGamesViewDetails;
use commands\Game\presenter\GameHittersPresenter;
use commands\Game\presenter\GameHittersViewDetails;
use commands\Game\presenter\GamePitchersPresenter;
use commands\Game\presenter\GamePitchersViewDetails;
use commands\Game\presenter\GamePresenter;
use commands\Game\presenter\GameViewDetails;
use controller\Request;
use domain\Game;
use domain\Player;

class GameCommand extends Command {

    protected function doExecute(Request $request) {
        switch($request->getProperty("action")) {
            case "add" :
                $addGameViewDetails = new AddGameViewDetails();

                if(isset($_POST['addGameFormSubmit'])) {// form was submitted
                    $this->handleAddGameFormSubmit($addGameViewDetails);
                    $addGameViewDetails->setFormSubmitted(true);
                }

                $addGamePresenter = new AddGamePresenter($addGameViewDetails);
                $addGamePresenter->create();
                break;
            case "all":
                $allGamesViewDetails = new AllGamesViewDetails();
                $allGames = Game::findAllGames();
                $allGamesViewDetails->setGames($allGames);
                $allGamesPresenter = new AllGamesPresenter($allGamesViewDetails);
                $allGamesPresenter->create();
                break;
            case "siteTransfer":
                if($_GET['season'] == "Career") {
                    if($_GET['type'] == "pitching") {
                        $this->findCareerPitchingInfo();
                    } else {
                        $this->findCareerHittingInfo();
                    }
                } else {
                    $fallYear = $_GET['season'] - 1;
                    $springYear = $_GET['season'];
                    if($_GET['type'] == "pitching") {
                        $this->findSeasonPitchingInfo($fallYear, $springYear);
                    } else {
                        $this->findSeasonHittingInfo($fallYear, $springYear);
                    }
                }
                break;
            default:
                $game = Game::findGameById($request->getProperty("action"));
                if($game !== null && $request->getExtras()[0] == "pitchers") {
                    if(isset($_POST['addPitchersSubmitForm'])) {
                        $this->handleAddPitchersForm($_POST, $game, $game->getAllPitcherIds());
                    }

                    $gamePitchersViewDetails = new GamePitchersViewDetails();
                    $gamePitchersViewDetails->setGame($game);
                    $gamePitchersViewDetails->setPlayers(Player::findAllPlayers());
                    $gamePitchersViewDetails->setPitchers($game->getPitchersArray());

                    $gamePitchersPresenter = new GamePitchersPresenter($gamePitchersViewDetails);
                    $gamePitchersPresenter->create();
                } else if($game !== null && $request->getExtras()[0] == "hitters") {
                    if(isset($_POST['addHittersSubmitForm'])) {
                        $this->handleAddHittersForm($_POST, $game, $game->getAllHitterIds());
                    }

                    $gameHittersViewDetails = new GameHittersViewDetails();
                    $gameHittersViewDetails->setGame($game);
                    $gameHittersViewDetails->setPlayers(Player::findAllPlayers());
                    $gameHittersViewDetails->setHitters($game->getHittersArray());

                    $gameHittersPresenter = new GameHittersPresenter($gameHittersViewDetails);
                    $gameHittersPresenter->create();
                } else {
                    $gameViewDetails = new GameViewDetails();

                    if($game !== null) {
                        $gameViewDetails->setGame($game);
                    } else {
                        $gameViewDetails->setGame(null);
                    }
                    $gamePresenter = new GamePresenter($gameViewDetails);
                    $gamePresenter->create();
                    break;
                }
        }
    }

    private function findSeasonPitchingInfo($fallYear, $springYear) {
        $seasonPitchersArray = Game::getSeasonPitchersArray($fallYear, $springYear);
        $pitchersMap = array();
        $uniqueGames = array();
        foreach($seasonPitchersArray as $seasonPitcher) {
            if($pitchersMap[$seasonPitcher['playerId']]) {// already in the map
                $pitchersMap[$seasonPitcher['playerId']]['won'] +=  $seasonPitcher['won'];
                $pitchersMap[$seasonPitcher['playerId']]['loss'] +=  $seasonPitcher['loss'];
                $pitchersMap[$seasonPitcher['playerId']]['gs'] +=  $seasonPitcher['gs'];
                $pitchersMap[$seasonPitcher['playerId']]['cg'] +=  $seasonPitcher['cg'];
                $pitchersMap[$seasonPitcher['playerId']]['sv'] +=  $seasonPitcher['sv'];
                $pitchersMap[$seasonPitcher['playerId']]['svo'] +=  $seasonPitcher['svo'];
                $pitchersMap[$seasonPitcher['playerId']]['ip'] +=  $seasonPitcher['ip'];
                $pitchersMap[$seasonPitcher['playerId']]['h'] +=  $seasonPitcher['h'];
                $pitchersMap[$seasonPitcher['playerId']]['r'] +=  $seasonPitcher['r'];
                $pitchersMap[$seasonPitcher['playerId']]['er'] +=  $seasonPitcher['er'];
                $pitchersMap[$seasonPitcher['playerId']]['hr'] +=  $seasonPitcher['hr'];
                $pitchersMap[$seasonPitcher['playerId']]['bb'] +=  $seasonPitcher['bb'];
                $pitchersMap[$seasonPitcher['playerId']]['so'] +=  $seasonPitcher['so'];
                $pitchersMap[$seasonPitcher['playerId']]['hbp'] +=  $seasonPitcher['hbp'];
                $pitchersMap[$seasonPitcher['playerId']]['sho'] +=  $seasonPitcher['sho'];
                $pitchersMap[$seasonPitcher['playerId']]['g'] +=  $seasonPitcher['g'];
                $pitchersMap[$seasonPitcher['playerId']]['firstName'] =  $seasonPitcher['playerFirstName'];
                $pitchersMap[$seasonPitcher['playerId']]['lastName'] =  $seasonPitcher['playerLastName'];

                if(!in_array($seasonPitcher['gameId'], $uniqueGames)) {
                    array_push($uniqueGames, $seasonPitcher['gameId']);
                }
            } else {
                $pitchersMap[$seasonPitcher['playerId']] = array('playerId' => $seasonPitcher['playerId'], 'won' => $seasonPitcher['won'], 'loss' => $seasonPitcher['loss'],
                        'gs' => $seasonPitcher['gs'], 'cg' => $seasonPitcher['cg'], 'sv' => $seasonPitcher['sv'], 'svo' => $seasonPitcher['svo'], 'ip' => $seasonPitcher['ip'],
                        'h' => $seasonPitcher['h'], 'r' => $seasonPitcher['r'], 'er' => $seasonPitcher['er'], 'hr' => $seasonPitcher['hr'], 'bb' => $seasonPitcher['bb'],
                        'so' => $seasonPitcher['so'], 'hbp' => $seasonPitcher['hbp'], 'sho' => $seasonPitcher['sho'], 'g' => $seasonPitcher['g'], 'firstName' => $seasonPitcher['playerFirstName'],
                        'lastName' => $seasonPitcher['playerLastName'], "gamePitchersId" => $seasonPitcher['gameId']);

                if(!in_array($seasonPitcher['gameId'], $uniqueGames)) {
                    array_push($uniqueGames, $seasonPitcher['gameId']);
                }
            }
        }

        $returnArray = array();
        $totalArray = array();
        $totalArray['name'] = "total";
        $totalArray['displayName'] = "Total";
        $realTotalIp = 0;
        foreach($pitchersMap as $pitcher) {
            // returnArray will hold all the stats that will show in the table. We should only be encountering each pitcher once in this for loop
            $individualArray = array();
            $individualArray['displayName'] = $pitcher['firstName']. " ".$pitcher['lastName'];
            $individualArray['name'] = strtolower($pitcher['firstName'][0]).$pitcher['lastName'];
            $individualArray['w'] = $pitcher['won'];
            $totalArray['w'] += $individualArray['w'];
            $individualArray['l'] = $pitcher['loss'];
            $totalArray['l'] += $individualArray['l'];

            $whole = floor($pitcher['ip']);
            $fraction = round(($pitcher['ip'] - $whole) * 10,0);// should only be 0, .1 or .2

            $mod = round(fmod($fraction, 3));
            $div = (int)($fraction / 3);
            $add = $div + ($mod / 10);
            $final = $whole + $add;

            $realIp = $whole + ($fraction * .33);

            if($pitcher['ip'] > 0) {
                $individualArray['era'] = round(($pitcher['er'] / $realIp) * 9, 3);
                $individualArray['era'] = number_format($individualArray['era'], 3);
            } else {
                $individualArray['era'] = "&#8734";
            }
            $individualArray['g'] = $pitcher['g'];
            $totalArray['g'] += $individualArray['g'];

            $individualArray['gs'] = $pitcher['gs'];
            $totalArray['gs'] += $individualArray['gs'];
            $individualArray['cg'] = $pitcher['cg'];
            $totalArray['cg'] += $individualArray['cg'];
            $individualArray['sv'] = $pitcher['sv'];
            $totalArray['sv'] += $individualArray['sv'];
            $individualArray['svo'] = $pitcher['svo'];
            $totalArray['svo'] += $individualArray['svo'];
            $individualArray['ip'] =  $individualArray['ip'] = number_format($final, 1);//number_format($pitcher['ip'],1 );
            $totalArray['ip'] += $pitcher['ip'];
            $realTotalIp += $pitcher['ip'];

            $whole = floor($totalArray['ip']);
            $fraction = round((($totalArray['ip'] - $whole) * 10));
            $part = 0;
            if($fraction > 2) {
                $part = $fraction / 3;
                $partWhole = floor($part);
                $partFraction = (int) (($part - $partWhole) * 10);
                $partFraction = ($partFraction / 3) / 10;

                $whole += $partWhole;
                $whole += $partFraction;
                $totalArray['ip'] = $whole;// number_format($whole + $part, 1);
            }

            $individualArray['h'] = $pitcher['h'];
            $totalArray['h'] += $individualArray['h'];
            $individualArray['r'] = $pitcher['r'];
            $totalArray['r'] += $individualArray['r'];
            $individualArray['er'] = $pitcher['er'];
            $totalArray['er'] += $individualArray['er'];
            $individualArray['hr'] = $pitcher['hr'];
            $totalArray['hr'] += $individualArray['hr'];
            $individualArray['bb'] = $pitcher['bb'];
            $totalArray['bb'] += $individualArray['bb'];
            $individualArray['so'] = $pitcher['so'];
            $totalArray['so'] += $individualArray['so'];




            if($pitcher['ip'] > 0) {
                $individualArray['whip'] = number_format(round(($pitcher['bb'] +  $pitcher['h']) /  $realIp, 3), 3);
            } else {
                $individualArray['whip'] = "&#8734";
            }
            $individualArray['sho'] = $pitcher['sho'];
            $totalArray['sho'] += $individualArray['sho'];
            $individualArray['hbp'] = $pitcher['hbp'];
            $totalArray['hbp'] += $individualArray['hbp'];
            if(($pitcher['won'] + $pitcher['loss']) > 0) {
                $individualArray['wper'] = number_format(round($pitcher['won'] / ($pitcher['won'] + $pitcher['loss']), 3), 3);
            } else {
                $individualArray['wper'] = number_format(0.000, 3);
            }
            if($pitcher['ip'] > 0) {
                $individualArray['kper9'] = number_format(round(($pitcher['so'] / $realIp) * 9, 3), 3);
            } else {
                $individualArray['kper9'] = number_format(0.000, 3);
            }
            if($pitcher['ip'] > 0) {
                $individualArray['wper9'] = number_format(round(($pitcher['bb'] / $realIp) * 9, 3), 3);
            } else {
                $individualArray['wper9'] = "&#8734";
            }
            if($pitcher['ip'] > 0) {
                $individualArray['hper9'] = number_format(round(($pitcher['h'] / $realIp) * 9, 3), 3);
            } else {
                $individualArray['hper9'] = "&#8734";
            }

            if($pitcher['bb'] == 0 && $pitcher['so'] == 0) {
                $individualArray['kbb'] = number_format(0.000, 3);
            } else if($pitcher['bb'] == 0) {
                $individualArray['kbb'] = number_format(0.000, 3);
            } else if($pitcher['so'] == 0) {
                $individualArray['kbb'] = number_format(0.000, 3);
            } else {
                $individualArray['kbb'] = number_format(round($pitcher['so'] / $pitcher['bb'], 3), 3);
            }

            array_push($returnArray, $individualArray);
        }
        if($springYear === "2014") {
            $totalArray['g'] = 18;
        } else {
            $totalArray['g'] = Game::getNumGamesPlayedForSeason($fallYear, $springYear);// count($uniqueGames);count($uniqueGames);
        }

        if($totalArray['ab'] > 0) {
            $totalArray['avg'] = number_format(round(( $totalArray['h'] / $totalArray['ab']), 3), 3);
        } else {
            $totalArray['avg'] = number_format(0.000, 3);
        }
        if($totalArray['ip'] > 0) {
            $totalArray['era'] = number_format(round(($totalArray['er'] / $realTotalIp) * 9, 3), 3);
        } else {
            $totalArray['era'] = "&#8734";
        }
        if($totalArray['ip'] > 0) {
            $totalArray['whip'] = number_format(round(($totalArray['bb'] +  $totalArray['h']) /  $realTotalIp, 3), 3);
        } else {
            $totalArray['whip'] = "&#8734";
        }
        if(($totalArray['w'] + $totalArray['l']) > 0) {
            $totalArray['wper'] = number_format(round($totalArray['w'] / ($totalArray['w'] + $totalArray['l']), 3), 3);
        } else {
            $totalArray['wper'] = number_format(0.000, 3);
        }
        if($totalArray['ip'] > 0) {
            $totalArray['kper9'] = number_format(round(($totalArray['so'] / $realTotalIp) * 9, 3), 3);
        } else {
            $totalArray['kper9'] = number_format(0.000, 3);
        }
        if($totalArray['ip'] > 0) {
            $totalArray['wper9'] = number_format(round(($totalArray['bb'] / $realTotalIp) * 9, 3), 3);
        } else {
            $totalArray['wper9'] = "&#8734";
        }
        if($totalArray['ip'] > 0) {
            $totalArray['hper9'] = number_format(round(($totalArray['h'] / $realTotalIp) * 9, 3), 3);
        } else {
            $totalArray['hper9'] = "&#8734";
        }
        if($totalArray['bb'] == 0 && $totalArray['so'] == 0) {
            $totalArray['kbb'] = number_format(0.000, 3);
        } else if($totalArray['bb'] == 0) {
            $totalArray['kbb'] = number_format(0.000, 3);
        } else if($totalArray['so'] == 0) {
            $totalArray['kbb'] = number_format(0.000, 3);
        } else {
            $totalArray['kbb'] = number_format(round($totalArray['so'] / $totalArray['bb'], 3), 3);
        }

        array_push($returnArray, $totalArray);
        $data = array('success' => true, 'stats' => $returnArray);
        echo "callback(" . (json_encode($data)).")";
    }

    private function findSeasonHittingInfo($fallYear, $springYear) {
        $seasonHittersArray = Game::getSeasonHittersArray($fallYear, $springYear);
        $hittersMap = array();
        foreach($seasonHittersArray as $seasonHitter) {
            if($hittersMap[$seasonHitter['playerId']]) {// already in the map
                $hittersMap[$seasonHitter['playerId']]['g'] +=  $seasonHitter['g'];
                $hittersMap[$seasonHitter['playerId']]['ab'] +=  $seasonHitter['ab'];
                $hittersMap[$seasonHitter['playerId']]['r'] +=  $seasonHitter['r'];
                $hittersMap[$seasonHitter['playerId']]['h'] +=  $seasonHitter['h'];
                $hittersMap[$seasonHitter['playerId']]['2b'] +=  $seasonHitter['2b'];
                $hittersMap[$seasonHitter['playerId']]['3b'] +=  $seasonHitter['3b'];
                $hittersMap[$seasonHitter['playerId']]['hr'] +=  $seasonHitter['hr'];
                $hittersMap[$seasonHitter['playerId']]['rbi'] +=  $seasonHitter['rbi'];
                $hittersMap[$seasonHitter['playerId']]['bb'] +=  $seasonHitter['bb'];
                $hittersMap[$seasonHitter['playerId']]['so'] +=  $seasonHitter['so'];
                $hittersMap[$seasonHitter['playerId']]['sb'] +=  $seasonHitter['sb'];
                $hittersMap[$seasonHitter['playerId']]['cs'] +=  $seasonHitter['cs'];
                $hittersMap[$seasonHitter['playerId']]['ibb'] +=  $seasonHitter['ibb'];
                $hittersMap[$seasonHitter['playerId']]['hbp'] +=  $seasonHitter['hbp'];
                $hittersMap[$seasonHitter['playerId']]['sacb'] +=  $seasonHitter['sacb'];
                $hittersMap[$seasonHitter['playerId']]['sacf'] +=  $seasonHitter['sacf'];
                $hittersMap[$seasonHitter['playerId']]['pa'] +=  $seasonHitter['pa'];
                $hittersMap[$seasonHitter['playerId']]['firstName'] =  $seasonHitter['playerFirstName'];
                $hittersMap[$seasonHitter['playerId']]['lastName'] =  $seasonHitter['playerLastName'];
            } else {
                $hittersMap[$seasonHitter['playerId']] = array('playerId' => $seasonHitter['playerId'], 'g' => $seasonHitter['g'], 'ab' => $seasonHitter['ab'],
                    'r' => $seasonHitter['r'], 'h' => $seasonHitter['h'], '2b' => $seasonHitter['2b'], '3b' => $seasonHitter['3b'], 'hr' => $seasonHitter['hr'],
                    'rbi' => $seasonHitter['rbi'], 'bb' => $seasonHitter['bb'], 'so' => $seasonHitter['so'], 'sb' => $seasonHitter['sb'], 'cs' => $seasonHitter['cs'],
                    'ibb' => $seasonHitter['ibb'], 'hbp' => $seasonHitter['hbp'], 'sacb' => $seasonHitter['sacb'], 'sacf' => $seasonHitter['sacf'], 'pa' => $seasonHitter['pa'], 'firstName' => $seasonHitter['playerFirstName'],
                    'lastName' => $seasonHitter['playerLastName'], "gameHittersId" => $seasonHitter['gameId']);
            }
        }

        $returnArray = array();
        $totalArray = array();
        $totalArray['name'] = "total";
        $totalArray['displayName'] = "Total";
        $uniqueGames = array();
        foreach($hittersMap as $hitter) {
            // returnArray will hold all the stats that will show in the table. We should only be encountering each pitcher once in this for loop
            $individualArray = array();
            $individualArray['displayName'] = $hitter['firstName']. " ".$hitter['lastName'];
            $fullLastName = explode(" ", trim($hitter['lastName']));
            $lastName = array_pop($fullLastName);// handles last names like "Van Patten". Will return "Patten".
            $individualArray['name'] = strtolower($hitter['firstName'][0]).$lastName;
            $individualArray['g'] = $hitter['g'];
            if(!in_array($hitter['gameHittersId'], $uniqueGames)) {
                array_push($uniqueGames, $hitter['gameHittersId']);
            }
            $individualArray['ab'] = $hitter['ab'];
            $totalArray['ab'] += $individualArray['ab'];
            $individualArray['r'] = $hitter['r'];
            $totalArray['r'] += $individualArray['r'];
            $individualArray['h'] = $hitter['h'];
            $totalArray['h'] += $individualArray['h'];
            $individualArray['2b'] = $hitter['2b'];
            $totalArray['2b'] += $individualArray['2b'];
            $individualArray['3b'] = $hitter['3b'];
            $totalArray['3b'] += $individualArray['3b'];
            $individualArray['hr'] = $hitter['hr'];
            $totalArray['hr'] += $individualArray['hr'];
            $individualArray['rbi'] = $hitter['rbi'];
            $totalArray['rbi'] += $individualArray['rbi'];
            $individualArray['bb'] = $hitter['bb'];
            $totalArray['bb'] += $individualArray['bb'];
            $individualArray['so'] = $hitter['so'];
            $totalArray['so'] += $individualArray['so'];
            $individualArray['sb'] = $hitter['sb'];
            $totalArray['sb'] += $individualArray['sb'];
            $individualArray['cs'] = $hitter['cs'];
            $totalArray['cs'] += $individualArray['cs'];
            if($hitter['ab'] > 0) {
                $individualArray['avg'] = round(( $hitter['h'] / $hitter['ab']), 3);
                $individualArray['avg'] = number_format($individualArray['avg'], 3);
            } else {
                $individualArray['avg'] = number_format(0.000, 3);
            }
            $obp = 0;
            if(($hitter['ab'] + $hitter['bb'] + $hitter['hbp'] + $hitter['sacf']) > 0) {
                $individualArray['obp'] = round(($hitter['h'] + $hitter['bb'] + $hitter['hbp']) / ($hitter['ab'] + $hitter['bb'] + $hitter['hbp'] + $hitter['sacf']), 3);
                $obp = $individualArray['obp'];
                $totalArray['obp'] += $individualArray['obp'];
                $individualArray['obp'] = number_format($individualArray['obp'], 3);
            } else {
                $individualArray['obp'] = number_format(0.000, 3);
                $obp = 0;
            }

            $individualArray['ibb'] = $hitter['ibb'];
            $totalArray['ibb'] += $individualArray['ibb'];
            $individualArray['hbp'] = $hitter['hbp'];
            $totalArray['hbp'] += $individualArray['hbp'];
            $individualArray['sacb'] = $hitter['sacb'];
            $totalArray['sacb'] += $individualArray['sacb'];
            $individualArray['sacf'] = $hitter['sacf'];
            $totalArray['sacf'] += $individualArray['sacf'];

            $singles = $hitter['h'] - ($hitter['2b'] + $hitter['3b'] + $hitter['hr']);
            $individualArray['tb'] = $singles + ($hitter['2b'] * 2) + ($hitter['3b'] * 3) + $hitter['hr'] * 4;
            $totalArray['tb'] += $individualArray['tb'];

            $slg = 0;
            if($hitter['ab'] > 0) {
                $individualArray['slg'] = round($individualArray['tb'] / $hitter['ab'], 3);
                $slg = $individualArray['slg'];
                $totalArray['slg'] += $individualArray['slg'];
                $individualArray['slg'] = number_format($individualArray['slg'], 3);
            } else {
                $individualArray['slg'] = number_format(0.000, 3);
                $slg = 0;
            }

            if($individualArray['slg'] > 0) {
                $individualArray['ops'] = $obp + $slg;
                $totalArray['ops'] += $individualArray['ops'];
                $individualArray['ops'] = number_format($individualArray['ops'], 3);
            } else {
                $individualArray['ops'] = number_format(0.000, 3);
            }

            $individualArray['xbh'] = $hitter['2b'] + $hitter['3b'] + $hitter['hr'];
            $totalArray['xbh'] += $individualArray['xbh'];

            $individualArray['pa'] = $hitter['ab'] + $hitter['bb'] + $hitter['ibb'] +  $hitter['hbp'] + $hitter['sacf'] + $hitter['sacb'];
            $totalArray['pa'] += $individualArray['pa'];

            array_push($returnArray, $individualArray);
        }
        if($springYear === "2014") {
            $totalArray['g'] = 18;
        } else {
            $totalArray['g'] = Game::getNumGamesPlayedForSeason($fallYear, $springYear);// count($uniqueGames);
        }
        if($totalArray['ab'] > 0) {
            $totalArray['avg'] = number_format(round(( $totalArray['h'] / $totalArray['ab']), 3), 3);
        } else {
            $totalArray['avg'] = number_format(0.000, 3);
        }
        $totalObp = 0;
        if(($totalArray['ab'] + $totalArray['bb'] + $totalArray['hbp'] + $totalArray['sacf']) > 0) {
            $totalArray['obp'] = round(($totalArray['h'] + $totalArray['bb'] + $totalArray['hbp']) / ($totalArray['ab'] + $totalArray['bb'] + $totalArray['hbp'] + $totalArray['sacf']), 3);
            $totalObp = $totalArray['obp'];
            $totalArray['obp'] = number_format($totalArray['obp'], 3);
        } else {
            $totalArray['obp'] = number_format(0.000, 3);
            $totalObp = 0;
        }
        $totalSlg = 0;
        if($totalArray['ab'] > 0) {
            $totalArray['slg'] = round($totalArray['tb'] / $totalArray['ab'], 3);
            $totalSlg = $totalArray['slg'];
            $totalArray['slg'] = number_format($totalArray['slg'], 3);
        } else {
            $totalArray['slg'] = number_format(0.000, 3);
            $totalSlg = 0;
        }
        if($totalArray['slg'] > 0) {
            $totalArray['ops'] = $totalObp + $totalSlg;
            $totalArray['ops'] = number_format($totalArray['ops'], 3);
        } else {
            $totalArray['ops'] = number_format(0.000, 3);
        }
        array_push($returnArray, $totalArray);

        $data = array('success' => true, 'stats' => $returnArray);
        echo "callback(" . (json_encode($data)).")";
    }

    private function handleAddGameFormSubmit(AddGameViewDetails $addGameViewDetails) {
        $errors = null;
        if(isset($_POST['opponent']) && strlen(trim($_POST['opponent'])) > 0) {
            if(strlen(trim($_POST['opponent'])) > 150) {
                $errors = "Opponent must be less than 150 characters";
            }
        } else {
            $errors = "Opponent is required";
        }

        if(isset($_POST['month']) && strlen(trim($_POST['month'])) > 0) {
            if(strlen(trim($_POST['month'])) > 2) {
                if(strlen($errors) > 0) {
                    $errors .= ", Month must be less than 2 characters";
                } else {
                    $errors = "Month must be less than 2 characters";
                }
            } else if(!is_numeric($_POST['month'])) {
                if(strlen($errors) > 0) {
                    $errors .= ", Month must be numeric";
                } else {
                    $errors = "Month must be numeric";
                }
            }
        } else {
            if(strlen($errors) > 0) {
                $errors .= ", Month is required";
            } else {
                $errors = "Month is required";
            }
        }

        if(isset($_POST['day']) && strlen(trim($_POST['day'])) > 0) {
            if(strlen(trim($_POST['day'])) > 2) {
                if(strlen($errors) > 0) {
                    $errors .= ", Day must be less than 2 characters";
                } else {
                    $errors = "Day must be less than 2 characters";
                }
            } else if(!is_numeric($_POST['day'])) {
                if(strlen($errors) > 0) {
                    $errors .= ", Day must be numeric";
                } else {
                    $errors = "Day must be numeric";
                }
            }
        } else {
            if(strlen($errors) > 0) {
                $errors .= ", Day is required";
            } else {
                $errors = "Day is required";
            }
        }

        if(isset($_POST['year']) && strlen(trim($_POST['year'])) > 0) {
            if(strlen(trim($_POST['year'])) != 4) {
                if(strlen($errors) > 0) {
                    $errors .= ", Year must be 4 characters";
                } else {
                    $errors = "Year must be 4 characters";
                }
            } else if(!is_numeric($_POST['year'])) {
                if(strlen($errors) > 0) {
                    $errors .= ", Year must be numeric";
                } else {
                    $errors = "Year must be numeric";
                }
            }
        } else {
            if(strlen($errors) > 0) {
                $errors .= ", Year is required";
            } else {
                $errors = "Year is required";
            }
        }

        if($_POST['winLoss'] != "w" && $_POST['winLoss'] !=  "l") {
            if(strlen($errors) > 0) {
                $errors .= ", Invalid value for win/loss";
            } else {
                $errors = "Invalid value for win/loss";
            }
        }

        if(strlen($errors) > 0) {
            $errors = "Error: " . $errors;
        } else {
            $newGame = new Game();
            $newGame->setVs($_POST['opponent']);
            $newGame->setDay($_POST['day']);
            $newGame->setMonth($_POST['month']);
            $newGame->setYear($_POST['year']);
            if($_POST['winLoss'] == "w") {
                $newGame->setWon(1);
            } else {
                $newGame->setWon(0);
            }

            if($newGame->doInsert()) {
                $addGameViewDetails->setGameAdded($newGame->getMonth()."/".$newGame->getDay()."/".$newGame->getYear(). " Game against ".$newGame->getVs()." added successfully!");
            } else {
                $errors = "Error inserting Game. Please try again and contact the system administrator if the problem continues";
            }
        }

        $addGameViewDetails->setErrors($errors);
    }

    private function findCareerPitchingInfo() {
        $seasonPitchersArray = Game::getCareerPitchersArray();
        $pitchersMap = array();
        $uniqueGames = array();
        foreach($seasonPitchersArray as $seasonPitcher) {
            if($pitchersMap[$seasonPitcher['playerId']]) {// already in the map
                $pitchersMap[$seasonPitcher['playerId']]['won'] +=  $seasonPitcher['won'];
                $pitchersMap[$seasonPitcher['playerId']]['loss'] +=  $seasonPitcher['loss'];
                $pitchersMap[$seasonPitcher['playerId']]['gs'] +=  $seasonPitcher['gs'];
                $pitchersMap[$seasonPitcher['playerId']]['cg'] +=  $seasonPitcher['cg'];
                $pitchersMap[$seasonPitcher['playerId']]['sv'] +=  $seasonPitcher['sv'];
                $pitchersMap[$seasonPitcher['playerId']]['svo'] +=  $seasonPitcher['svo'];
                $pitchersMap[$seasonPitcher['playerId']]['ip'] +=  $seasonPitcher['ip'];
                $pitchersMap[$seasonPitcher['playerId']]['h'] +=  $seasonPitcher['h'];
                $pitchersMap[$seasonPitcher['playerId']]['r'] +=  $seasonPitcher['r'];
                $pitchersMap[$seasonPitcher['playerId']]['er'] +=  $seasonPitcher['er'];
                $pitchersMap[$seasonPitcher['playerId']]['hr'] +=  $seasonPitcher['hr'];
                $pitchersMap[$seasonPitcher['playerId']]['bb'] +=  $seasonPitcher['bb'];
                $pitchersMap[$seasonPitcher['playerId']]['so'] +=  $seasonPitcher['so'];
                $pitchersMap[$seasonPitcher['playerId']]['hbp'] +=  $seasonPitcher['hbp'];
                $pitchersMap[$seasonPitcher['playerId']]['sho'] +=  $seasonPitcher['sho'];
                $pitchersMap[$seasonPitcher['playerId']]['g'] +=  $seasonPitcher['g'];
                $pitchersMap[$seasonPitcher['playerId']]['firstName'] =  $seasonPitcher['playerFirstName'];
                $pitchersMap[$seasonPitcher['playerId']]['lastName'] =  $seasonPitcher['playerLastName'];

                if(!in_array($seasonPitcher['gameId'], $uniqueGames)) {
                    array_push($uniqueGames, $seasonPitcher['gameId']);
                }
            } else {
                $pitchersMap[$seasonPitcher['playerId']] = array('playerId' => $seasonPitcher['playerId'], 'won' => $seasonPitcher['won'], 'loss' => $seasonPitcher['loss'],
                    'gs' => $seasonPitcher['gs'], 'cg' => $seasonPitcher['cg'], 'sv' => $seasonPitcher['sv'], 'svo' => $seasonPitcher['svo'], 'ip' => $seasonPitcher['ip'],
                    'h' => $seasonPitcher['h'], 'r' => $seasonPitcher['r'], 'er' => $seasonPitcher['er'], 'hr' => $seasonPitcher['hr'], 'bb' => $seasonPitcher['bb'],
                    'so' => $seasonPitcher['so'], 'hbp' => $seasonPitcher['hbp'], 'sho' => $seasonPitcher['sho'], 'g' => $seasonPitcher['g'], 'firstName' => $seasonPitcher['playerFirstName'],
                    'lastName' => $seasonPitcher['playerLastName'], "gamePitchersId" => $seasonPitcher['gameId']);

                if(!in_array($seasonPitcher['gameId'], $uniqueGames)) {
                    array_push($uniqueGames, $seasonPitcher['gameId']);
                }
            }
        }

        $returnArray = array();
        $totalArray = array();
        $totalArray['name'] = "total";
        $totalArray['displayName'] = "Total";
        foreach($pitchersMap as $pitcher) {
            // returnArray will hold all the stats that will show in the table. We should only be encountering each pitcher once in this for loop
            $individualArray = array();
            $individualArray['displayName'] = $pitcher['firstName']. " ".$pitcher['lastName'];
            $individualArray['name'] = strtolower($pitcher['firstName'][0]).$pitcher['lastName'];
            $individualArray['w'] = $pitcher['won'];
            $totalArray['w'] += $individualArray['w'];
            $individualArray['l'] = $pitcher['loss'];
            $totalArray['l'] += $individualArray['l'];

            $whole = floor($pitcher['ip']);
            $fraction = ($pitcher['ip'] - $whole) * 10;// should only be 0, .1 or .2
            $mod = round(fmod($fraction, 3));
            $div = (int)($fraction / 3);
            $add = $div + ($mod / 10);
            $final = $whole + $add;

            $realIp = $whole + ($fraction * .33);

            if($pitcher['ip'] > 0) {
                $individualArray['era'] = round(($pitcher['er'] / $realIp) * 9, 3);
                $individualArray['era'] = number_format($individualArray['era'], 3);
            } else {
                $individualArray['era'] = "&#8734";
            }
            $individualArray['g'] = $pitcher['g'];
            $totalArray['g'] += $individualArray['g'];

            $individualArray['gs'] = $pitcher['gs'];
            $totalArray['gs'] += $individualArray['gs'];
            $individualArray['cg'] = $pitcher['cg'];
            $totalArray['cg'] += $individualArray['cg'];
            $individualArray['sv'] = $pitcher['sv'];
            $totalArray['sv'] += $individualArray['sv'];
            $individualArray['svo'] = $pitcher['svo'];
            $totalArray['svo'] += $individualArray['svo'];
            $individualArray['ip'] = number_format($final, 1);//number_format($pitcher['ip'],1 );
            $totalArray['ip'] += $pitcher['ip'];
            $realTotalIp += $pitcher['ip'];

            $whole = floor($totalArray['ip']);
            $fraction = round((($totalArray['ip'] - $whole) * 10));
            $part = 0;
            if($fraction > 2) {
                $part = $fraction / 3;
                $partWhole = floor($part);
                $partFraction = (int) (($part - $partWhole) * 10);
                $partFraction = ($partFraction / 3) / 10;

                $whole += $partWhole;
                $whole += $partFraction;
                $totalArray['ip'] = $whole;// number_format($whole + $part, 1);
            }

            $individualArray['h'] = $pitcher['h'];
            $totalArray['h'] += $individualArray['h'];
            $individualArray['r'] = $pitcher['r'];
            $totalArray['r'] += $individualArray['r'];
            $individualArray['er'] = $pitcher['er'];
            $totalArray['er'] += $individualArray['er'];
            $individualArray['hr'] = $pitcher['hr'];
            $totalArray['hr'] += $individualArray['hr'];
            $individualArray['bb'] = $pitcher['bb'];
            $totalArray['bb'] += $individualArray['bb'];
            $individualArray['so'] = $pitcher['so'];
            $totalArray['so'] += $individualArray['so'];

            if($pitcher['ip'] > 0) {
                $individualArray['whip'] = number_format(round(($pitcher['bb'] +  $pitcher['h']) /  $realIp, 3), 3);
            } else {
                $individualArray['whip'] = "&#8734";
            }
            $individualArray['sho'] = $pitcher['sho'];
            $totalArray['sho'] += $individualArray['sho'];
            $individualArray['hbp'] = $pitcher['hbp'];
            $totalArray['hbp'] += $individualArray['hbp'];
            if(($pitcher['won'] + $pitcher['loss']) > 0) {
                $individualArray['wper'] = number_format(round($pitcher['won'] / ($pitcher['won'] + $pitcher['loss']), 3), 3);
            } else {
                $individualArray['wper'] = number_format(0.000, 3);
            }
            if($pitcher['ip'] > 0) {
                $individualArray['kper9'] = number_format(round(($pitcher['so'] / $realIp) * 9, 3), 3);
            } else {
                $individualArray['kper9'] = number_format(0.000, 3);
            }
            if($pitcher['ip'] > 0) {
                $individualArray['wper9'] = number_format(round(($pitcher['bb'] / $realIp) * 9, 3), 3);
            } else {
                $individualArray['wper9'] = "&#8734";
            }
            if($pitcher['ip'] > 0) {
                $individualArray['hper9'] = number_format(round(($pitcher['h'] / $realIp) * 9, 3), 3);
            } else {
                $individualArray['hper9'] = "&#8734";
            }

            if($pitcher['bb'] == 0 && $pitcher['so'] == 0) {
                $individualArray['kbb'] = number_format(0.000, 3);
            } else if($pitcher['bb'] == 0) {
                $individualArray['kbb'] = number_format(0.000, 3);
            } else if($pitcher['so'] == 0) {
                $individualArray['kbb'] = number_format(0.000, 3);
            } else {
                $individualArray['kbb'] = number_format(round($pitcher['so'] / $pitcher['bb'], 3), 3);
            }

            array_push($returnArray, $individualArray);
        }

        $totalArray['g'] = 18 + (count($uniqueGames) - 1);// 18 for first season total minus 1 for first season


        if($totalArray['ab'] > 0) {
            $totalArray['avg'] = number_format(round(( $totalArray['h'] / $totalArray['ab']), 3), 3);
        } else {
            $totalArray['avg'] = number_format(0.000, 3);
        }
        if($totalArray['ip'] > 0) {
            $totalArray['era'] = number_format(round(($totalArray['er'] / $realTotalIp) * 9, 3), 3);
        } else {
            $totalArray['era'] = "&#8734";
        }
        if($totalArray['ip'] > 0) {
            $totalArray['whip'] = number_format(round(($totalArray['bb'] +  $totalArray['h']) /  $realTotalIp, 3), 3);
        } else {
            $totalArray['whip'] = "&#8734";
        }
        if(($totalArray['w'] + $totalArray['l']) > 0) {
            $totalArray['wper'] = number_format(round($totalArray['w'] / ($totalArray['w'] + $totalArray['l']), 3), 3);
        } else {
            $totalArray['wper'] = number_format(0.000, 3);
        }
        if($totalArray['ip'] > 0) {
            $totalArray['kper9'] = number_format(round(($totalArray['so'] / $realTotalIp) * 9, 3), 3);
        } else {
            $totalArray['kper9'] = number_format(0.000, 3);
        }
        if($totalArray['ip'] > 0) {
            $totalArray['wper9'] = number_format(round(($totalArray['bb'] / $realTotalIp) * 9, 3), 3);
        } else {
            $totalArray['wper9'] = "&#8734";
        }
        if($totalArray['ip'] > 0) {
            $totalArray['hper9'] = number_format(round(($totalArray['h'] / $realTotalIp) * 9, 3), 3);
        } else {
            $totalArray['hper9'] = "&#8734";
        }
        if($totalArray['bb'] == 0 && $totalArray['so'] == 0) {
            $totalArray['kbb'] = number_format(0.000, 3);
        } else if($totalArray['bb'] == 0) {
            $totalArray['kbb'] = number_format(0.000, 3);
        } else if($totalArray['so'] == 0) {
            $totalArray['kbb'] = number_format(0.000, 3);
        } else {
            $totalArray['kbb'] = number_format(round($totalArray['so'] / $totalArray['bb'], 3), 3);
        }

        array_push($returnArray, $totalArray);
        $data = array('success' => true, 'stats' => $returnArray);
        echo "callback(" . (json_encode($data)).")";
    }

    private function findCareerHittingInfo() {
        $seasonHittersArray = Game::getCareerHittersArray();
        $hittersMap = array();
        $uniqueGames = array();
        foreach($seasonHittersArray as $seasonHitter) {
            if($hittersMap[$seasonHitter['playerId']]) {// already in the map
                $hittersMap[$seasonHitter['playerId']]['g'] +=  $seasonHitter['g'];
                $hittersMap[$seasonHitter['playerId']]['ab'] +=  $seasonHitter['ab'];
                $hittersMap[$seasonHitter['playerId']]['r'] +=  $seasonHitter['r'];
                $hittersMap[$seasonHitter['playerId']]['h'] +=  $seasonHitter['h'];
                $hittersMap[$seasonHitter['playerId']]['2b'] +=  $seasonHitter['2b'];
                $hittersMap[$seasonHitter['playerId']]['3b'] +=  $seasonHitter['3b'];
                $hittersMap[$seasonHitter['playerId']]['hr'] +=  $seasonHitter['hr'];
                $hittersMap[$seasonHitter['playerId']]['rbi'] +=  $seasonHitter['rbi'];
                $hittersMap[$seasonHitter['playerId']]['bb'] +=  $seasonHitter['bb'];
                $hittersMap[$seasonHitter['playerId']]['so'] +=  $seasonHitter['so'];
                $hittersMap[$seasonHitter['playerId']]['sb'] +=  $seasonHitter['sb'];
                $hittersMap[$seasonHitter['playerId']]['cs'] +=  $seasonHitter['cs'];
                $hittersMap[$seasonHitter['playerId']]['ibb'] +=  $seasonHitter['ibb'];
                $hittersMap[$seasonHitter['playerId']]['hbp'] +=  $seasonHitter['hbp'];
                $hittersMap[$seasonHitter['playerId']]['sacb'] +=  $seasonHitter['sacb'];
                $hittersMap[$seasonHitter['playerId']]['sacf'] +=  $seasonHitter['sacf'];
                $hittersMap[$seasonHitter['playerId']]['pa'] +=  $seasonHitter['pa'];
                $hittersMap[$seasonHitter['playerId']]['firstName'] =  $seasonHitter['playerFirstName'];
                $hittersMap[$seasonHitter['playerId']]['lastName'] =  $seasonHitter['playerLastName'];

                if(!in_array($seasonHitter['gameId'], $uniqueGames)) {
                    array_push($uniqueGames, $seasonHitter['gameId']);
                }
            } else {
                $hittersMap[$seasonHitter['playerId']] = array('playerId' => $seasonHitter['playerId'], 'g' => $seasonHitter['g'], 'ab' => $seasonHitter['ab'],
                    'r' => $seasonHitter['r'], 'h' => $seasonHitter['h'], '2b' => $seasonHitter['2b'], '3b' => $seasonHitter['3b'], 'hr' => $seasonHitter['hr'],
                    'rbi' => $seasonHitter['rbi'], 'bb' => $seasonHitter['bb'], 'so' => $seasonHitter['so'], 'sb' => $seasonHitter['sb'], 'cs' => $seasonHitter['cs'],
                    'ibb' => $seasonHitter['ibb'], 'hbp' => $seasonHitter['hbp'], 'sacb' => $seasonHitter['sacb'], 'sacf' => $seasonHitter['sacf'], 'pa' => $seasonHitter['pa'], 'firstName' => $seasonHitter['playerFirstName'],
                    'lastName' => $seasonHitter['playerLastName'], "gameHittersId" => $seasonHitter['gameId']);

                if(!in_array($seasonHitter['gameId'], $uniqueGames)) {
                    array_push($uniqueGames, $seasonHitter['gameId']);
                }
            }
        }

        $returnArray = array();
        $totalArray = array();
        $totalArray['name'] = "total";
        $totalArray['displayName'] = "Total";
        foreach($hittersMap as $hitter) {
            // returnArray will hold all the stats that will show in the table. We should only be encountering each pitcher once in this for loop
            $individualArray = array();
            $individualArray['displayName'] = $hitter['firstName']. " ".$hitter['lastName'];
            $fullLastName = explode(" ", trim($hitter['lastName']));
            $lastName = array_pop($fullLastName);// handles last names like "Van Patten". Will return "Patten".
            $individualArray['name'] = strtolower($hitter['firstName'][0]).$lastName;
            $individualArray['g'] = $hitter['g'];

            $individualArray['ab'] = $hitter['ab'];
            $totalArray['ab'] += $individualArray['ab'];
            $individualArray['r'] = $hitter['r'];
            $totalArray['r'] += $individualArray['r'];
            $individualArray['h'] = $hitter['h'];
            $totalArray['h'] += $individualArray['h'];
            $individualArray['2b'] = $hitter['2b'];
            $totalArray['2b'] += $individualArray['2b'];
            $individualArray['3b'] = $hitter['3b'];
            $totalArray['3b'] += $individualArray['3b'];
            $individualArray['hr'] = $hitter['hr'];
            $totalArray['hr'] += $individualArray['hr'];
            $individualArray['rbi'] = $hitter['rbi'];
            $totalArray['rbi'] += $individualArray['rbi'];
            $individualArray['bb'] = $hitter['bb'];
            $totalArray['bb'] += $individualArray['bb'];
            $individualArray['so'] = $hitter['so'];
            $totalArray['so'] += $individualArray['so'];
            $individualArray['sb'] = $hitter['sb'];
            $totalArray['sb'] += $individualArray['sb'];
            $individualArray['cs'] = $hitter['cs'];
            $totalArray['cs'] += $individualArray['cs'];
            if($hitter['ab'] > 0) {
                $individualArray['avg'] = round(( $hitter['h'] / $hitter['ab']), 3);
                $individualArray['avg'] = number_format($individualArray['avg'], 3);
            } else {
                $individualArray['avg'] = number_format(0.000, 3);
            }
            $obp = 0;
            if(($hitter['ab'] + $hitter['bb'] + $hitter['hbp'] + $hitter['sacf']) > 0) {
                $individualArray['obp'] = round(($hitter['h'] + $hitter['bb'] + $hitter['hbp']) / ($hitter['ab'] + $hitter['bb'] + $hitter['hbp'] + $hitter['sacf']), 3);
                $obp = $individualArray['obp'];
                $totalArray['obp'] += $individualArray['obp'];
                $individualArray['obp'] = number_format($individualArray['obp'], 3);
            } else {
                $individualArray['obp'] = number_format(0.000, 3);
                $obp = 0;
            }

            $individualArray['ibb'] = $hitter['ibb'];
            $totalArray['ibb'] += $individualArray['ibb'];
            $individualArray['hbp'] = $hitter['hbp'];
            $totalArray['hbp'] += $individualArray['hbp'];
            $individualArray['sacb'] = $hitter['sacb'];
            $totalArray['sacb'] += $individualArray['sacb'];
            $individualArray['sacf'] = $hitter['sacf'];
            $totalArray['sacf'] += $individualArray['sacf'];

            $singles = $hitter['h'] - ($hitter['2b'] + $hitter['3b'] + $hitter['hr']);
            $individualArray['tb'] = $singles + ($hitter['2b'] * 2) + ($hitter['3b'] * 3) + $hitter['hr'] * 4;
            $totalArray['tb'] += $individualArray['tb'];

            $slg = 0;
            if($hitter['ab'] > 0) {
                $individualArray['slg'] = round($individualArray['tb'] / $hitter['ab'], 3);
                $slg = $individualArray['slg'];
                $totalArray['slg'] += $individualArray['slg'];
                $individualArray['slg'] = number_format($individualArray['slg'], 3);
            } else {
                $individualArray['slg'] = number_format(0.000, 3);
                $slg = 0;
            }

            if($individualArray['slg'] > 0) {
                $individualArray['ops'] = $obp + $slg;
                $totalArray['ops'] += $individualArray['ops'];
                $individualArray['ops'] = number_format($individualArray['ops'], 3);
            } else {
                $individualArray['ops'] = number_format(0.000, 3);
            }

            $individualArray['xbh'] = $hitter['2b'] + $hitter['3b'] + $hitter['hr'];
            $totalArray['xbh'] += $individualArray['xbh'];

            $individualArray['pa'] = $hitter['ab'] + $hitter['bb'] + $hitter['ibb'] +  $hitter['hbp'] + $hitter['sacf'] + $hitter['sacb'];
            $totalArray['pa'] += $individualArray['pa'];

            array_push($returnArray, $individualArray);
        }

        $totalArray['g'] = 18 + (count($uniqueGames) - 1);// 18 for first season total minus 1 for first season
        if($totalArray['ab'] > 0) {
            $totalArray['avg'] = number_format(round(( $totalArray['h'] / $totalArray['ab']), 3), 3);
        } else {
            $totalArray['avg'] = number_format(0.000, 3);
        }
        $totalObp = 0;
        if(($totalArray['ab'] + $totalArray['bb'] + $totalArray['hbp'] + $totalArray['sacf']) > 0) {
            $totalArray['obp'] = round(($totalArray['h'] + $totalArray['bb'] + $totalArray['hbp']) / ($totalArray['ab'] + $totalArray['bb'] + $totalArray['hbp'] + $totalArray['sacf']), 3);
            $totalObp = $totalArray['obp'];
            $totalArray['obp'] = number_format($totalArray['obp'], 3);
        } else {
            $totalArray['obp'] = number_format(0.000, 3);
            $totalObp = 0;
        }
        $totalSlg = 0;
        if($totalArray['ab'] > 0) {
            $totalArray['slg'] = round($totalArray['tb'] / $totalArray['ab'], 3);
            $totalSlg = $totalArray['slg'];
            $totalArray['slg'] = number_format($totalArray['slg'], 3);
        } else {
            $totalArray['slg'] = number_format(0.000, 3);
            $totalSlg = 0;
        }
        if($totalArray['slg'] > 0) {
            $totalArray['ops'] = $totalObp + $totalSlg;
            $totalArray['ops'] = number_format($totalArray['ops'], 3);
        } else {
            $totalArray['ops'] = number_format(0.000, 3);
        }
        array_push($returnArray, $totalArray);

        $data = array('success' => true, 'stats' => $returnArray);
        echo "callback(" . (json_encode($data)).")";
    }

    private function handleAddPitchersForm(array $formData, Game $game, array $allPitcherIdsArray) {
        $pitchersInTableMap = array();
        foreach($formData['hiddenIdentifier'] as $playerId) {
            $player = Player::findPlayerById($playerId);
            if($player !== null) {
                $wins = $formData["w-".$playerId];
                $losses = $formData["l-".$playerId];
                $games = $formData["g-".$playerId];
                $gamesStarted = $formData["gs-".$playerId];
                $completeGames = $formData["cg-".$playerId];
                $saves = $formData["sv-".$playerId];
                $saveOpportunities = $formData["svo-".$playerId];
                $inningsPitched = $formData["ip-".$playerId];
                $hits = $formData["h-".$playerId];
                $runs = $formData["r-".$playerId];
                $earnedRuns = $formData["er-".$playerId];
                $homeRuns = $formData["hr-".$playerId];
                $walks = $formData["bb-".$playerId];
                $strikeOuts = $formData["so-".$playerId];
                $shutOuts = $formData["sho-".$playerId];
                $hitByPitch = $formData["hbp-".$playerId];

                if(!is_numeric($wins) || $wins < 0) {
                    $wins = 0;
                }

                if(!is_numeric($losses) || $losses < 0) {
                    $losses = 0;
                }

                if(!is_numeric($games) || $games < 0) {
                    $games = 0;
                }

                if(!is_numeric($gamesStarted) || $gamesStarted < 0) {
                    $gamesStarted = 0;
                }

                if(!is_numeric($completeGames) || $completeGames < 0) {
                    $completeGames = 0;
                }

                if(!is_numeric($saves) || $saves < 0) {
                    $saves = 0;
                }

                if(!is_numeric($saveOpportunities) || $saveOpportunities < 0) {
                    $saveOpportunities = 0;
                }

                if(!is_numeric($inningsPitched) || $inningsPitched < 0) {
                    $inningsPitched = 0;
                }

                if(!is_numeric($hits) || $hits < 0) {
                    $hits = 0;
                }

                if(!is_numeric($runs) || $runs < 0) {
                    $runs = 0;
                }

                if(!is_numeric($earnedRuns) || $earnedRuns < 0) {
                    $earnedRuns = 0;
                }

                if(!is_numeric($homeRuns) || $homeRuns < 0) {
                    $homeRuns = 0;
                }

                if(!is_numeric($walks) || $walks < 0) {
                    $walks = 0;
                }

                if(!is_numeric($strikeOuts) || $strikeOuts < 0) {
                    $strikeOuts = 0;
                }

                if(!is_numeric($shutOuts) || $shutOuts < 0) {
                    $shutOuts = 0;
                }

                if(!is_numeric($hitByPitch) || $hitByPitch < 0) {
                    $hitByPitch = 0;
                }


                // now check if this person already exists for this game
                if(Game::pitcherExistsForGame($playerId, $game->getGameId())) {
                    $game->updatePitcherForGame($playerId, $wins, $losses, $games, $gamesStarted, $completeGames, $saves, $saveOpportunities, $inningsPitched, $hits,
                        $runs, $earnedRuns, $homeRuns, $walks, $strikeOuts, $shutOuts, $hitByPitch);
                    $pitchersInTableMap[$playerId] = true;
                } else {
                    $game->addPitcherForGame($playerId, $wins, $losses, $games, $gamesStarted, $completeGames, $saves, $saveOpportunities, $inningsPitched, $hits,
                        $runs, $earnedRuns, $homeRuns, $walks, $strikeOuts, $shutOuts, $hitByPitch);
                }
            }
        }

        foreach($allPitcherIdsArray as $pitcherId) {
            if($pitchersInTableMap[$pitcherId] !== true) {
                $game->deletePitcher($pitcherId);
            }
        }
    }

    private function handleAddHittersForm(array $formData, Game $game, array $allHitterIdsArray) {
        $hittersInTableMap = array();
        foreach($formData['hiddenIdentifier'] as $playerId) {
            $player = Player::findPlayerById($playerId);
            if($player !== null) {
                $games = $formData["g-".$playerId];
                $atBats = $formData["ab-".$playerId];
                $runs = $formData["r-".$playerId];
                $hits = $formData["h-".$playerId];
                $doubles = $formData["b2-".$playerId];
                $triples = $formData["b3-".$playerId];
                $homeRuns = $formData["hr-".$playerId];
                $rbis = $formData["rbi-".$playerId];
                $walks = $formData["bb-".$playerId];
                $strikeOuts = $formData["so-".$playerId];
                $stolenBases = $formData["sb-".$playerId];
                $caughtStealing = $formData["cs-".$playerId];
                $intentionalWalks = $formData["ibb-".$playerId];
                $hitByPitch = $formData["hbp-".$playerId];
                $sacBunts = $formData["sacb-".$playerId];
                $sacFlys = $formData["sacf-".$playerId];

                if(!is_numeric($games) || $games < 0) {
                    $games = 0;
                }

                if(!is_numeric($atBats) || $atBats < 0) {
                    $atBats = 0;
                }

                if(!is_numeric($runs) || $runs < 0) {
                    $runs = 0;
                }

                if(!is_numeric($hits) || $hits < 0) {
                    $hits = 0;
                }

                if(!is_numeric($doubles) || $doubles < 0) {
                    $doubles = 0;
                }

                if(!is_numeric($triples) || $triples < 0) {
                    $triples = 0;
                }

                if(!is_numeric($homeRuns) || $homeRuns < 0) {
                    $homeRuns = 0;
                }

                if(!is_numeric($rbis) || $rbis < 0) {
                    $rbis = 0;
                }

                if(!is_numeric($walks) || $walks < 0) {
                    $walks = 0;
                }

                if(!is_numeric($strikeOuts) || $strikeOuts < 0) {
                    $strikeOuts = 0;
                }

                if(!is_numeric($stolenBases) || $stolenBases < 0) {
                    $stolenBases = 0;
                }

                if(!is_numeric($caughtStealing) || $caughtStealing < 0) {
                    $caughtStealing = 0;
                }

                if(!is_numeric($intentionalWalks) || $intentionalWalks < 0) {
                    $intentionalWalks = 0;
                }

                if(!is_numeric($hitByPitch) || $hitByPitch < 0) {
                    $hitByPitch = 0;
                }

                if(!is_numeric($sacBunts) || $sacBunts < 0) {
                    $sacBunts = 0;
                }

                if(!is_numeric($sacFlys) || $sacFlys < 0) {
                    $sacFlys = 0;
                }


                // now check if this person already exists for this game
                if(Game::hitterExistsForGame($playerId, $game->getGameId())) {
                    $game->updateHitterForGame($playerId, $games, $atBats, $runs, $hits, $doubles, $triples, $homeRuns, $rbis, $walks,
                        $strikeOuts, $stolenBases, $caughtStealing, $intentionalWalks, $hitByPitch, $sacBunts, $sacFlys);
                    $hittersInTableMap[$playerId] = true;
                } else {
                    $game->addHitterForGame($playerId, $games, $atBats, $runs, $hits, $doubles, $triples, $homeRuns, $rbis, $walks,
                        $strikeOuts, $stolenBases, $caughtStealing, $intentionalWalks, $hitByPitch, $sacBunts, $sacFlys);
                }
            }
        }

        foreach($allHitterIdsArray as $hitterId) {
            if($hittersInTableMap[$hitterId] !== true) {
                $game->deleteHitter($hitterId);
            }
        }
    }
}
?>