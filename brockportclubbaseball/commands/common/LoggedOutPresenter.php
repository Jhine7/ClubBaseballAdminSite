<?php
/**
 * Created by PhpStorm.
 * User: Justin
 * Date: 7/19/14
 * Time: 2:22 PM
 */

namespace commands\common;


use commands\common\view\LoggedOutView;

class LoggedOutPresenter {
    function __construct() {
    }

    function create() {
        $loggedOutView = new LoggedOutView();
        echo $loggedOutView->create();
    }
}
?>