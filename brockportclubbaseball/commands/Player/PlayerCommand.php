<?php
/**
 * Created by PhpStorm.
 * User: Justin
 * Date: 7/19/14
 * Time: 6:30 PM
 */

namespace commands\Player;


use commands\common\Command;
use commands\Player\presenter\PlayerPresenter;
use commands\Player\presenter\PlayerViewDetails;
use controller\Request;
use domain\Player;

class PlayerCommand extends Command {

    protected function doExecute(Request $request) {
        if(isset($_POST['addPlayerFormSubmit'])) {// form was submitted
            $playerViewDetails = new PlayerViewDetails();
            $playerViewDetails->setFormSubmitted(true);

            $errors = null;
            if(isset($_POST['firstName']) && strlen(trim($_POST['firstName'])) > 0) {
                if(strlen(trim($_POST['firstName'])) > 150) {
                    $errors = "First Name must be less than 150 characters";
                }
            } else {
                $errors = "First Name is required";
            }

            if(isset($_POST['lastName']) && strlen(trim($_POST['lastName'])) > 0) {
                if(strlen(trim($_POST['lastName'])) > 150) {
                    if(strlen($errors) > 0) {
                        $errors .= ", Last Name must be less than 150 characters";
                    } else {
                        $errors = "Last Name must be less than 150 characters";
                    }
                }
            } else {
                if(strlen($errors) > 0) {
                    $errors .= ", Last Name is required";
                } else {
                    $errors = "Last Name is required";
                }
            }

            if(strlen($errors) > 0) {
                $errors = "Error: " . $errors;
            } else {
                $newPlayer = new Player();
                $newPlayer->setPlayerFirstName($_POST['firstName']);
                $newPlayer->setPlayerLastName($_POST['lastName']);

                if($newPlayer->doInsert()) {
                    $playerViewDetails->setNameAdded($newPlayer->getPlayerFirstName()." ".$newPlayer->getPlayerLastName());
                } else {
                    $errors = "Error inserting Player. Please try again and contact the system administrator if the problem continues";
                }
            }

            $playerViewDetails->setErrors($errors);

            $playerPresenter = new PlayerPresenter($playerViewDetails);
            $playerPresenter->create();
        } else {
            $playerViewDetails = new PlayerViewDetails();
            $playerPresenter = new PlayerPresenter($playerViewDetails);
            $playerPresenter->create();
        }
    }
}
?>