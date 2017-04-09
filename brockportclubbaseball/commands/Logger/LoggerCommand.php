<?php
/**
 * Created by PhpStorm.
 * User: Justin
 * Date: 7/24/14
 * Time: 9:35 PM
 */

namespace commands\Logger;


use base\SessionRegistry;
use commands\common\Command;
use controller\Request;
use domain\Admin;

class LoggerCommand extends Command {
    const SALT_1 = "gmh@zh&";
    const SALT_2 = "pg!@*z@";

    function doExecute(Request $request) {
        if($request->getProperty("action") === "login") {
            $this->login();
        } else {
            $this->logout();
        }
    }

    function login() {
        $adminConfirmed = $this->confirmAdmin();
        if($adminConfirmed !== null) {
            $return['error'] = false;

            // set up the session
            SessionRegistry::instance()->setName($adminConfirmed->getAdminName());
            SessionRegistry::instance()->setAdminId($adminConfirmed->getAdminId());
            SessionRegistry::instance()->setLoggedInStatus(true);
        } else {
            // send back error to ajax
            $return['error'] = true;
            $return['msg'] = 'Invalid Name / Password';
        }

        echo json_encode($return);
    }

    function logout() {
        SessionRegistry::instance()->unsetName();
        SessionRegistry::instance()->unsetAdminId();
        SessionRegistry::instance()->setLoggedInStatus(false);
        $_SESSION = array(); // reset session array
        session_destroy();   // destroy session.

        // ALSO NEED TO UNSET COOKIES IF SET
    }

    function confirmAdmin() {
        $_POST['password'] = md5(LoggerCommand::SALT_1 . $_POST['password'] . LoggerCommand::SALT_2);

        $admin = Admin::findAdminByNameAndPassword($_POST["name"], $_POST['password']);

        return $admin;
    }
}
?>