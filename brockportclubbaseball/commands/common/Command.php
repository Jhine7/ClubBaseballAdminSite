<?php
/**
 * Created by PhpStorm.
 * User: Justin
 * Date: 4/12/14
 * Time: 1:10 AM
 */

namespace commands\common;
use base\SessionRegistry;
use \base\util\SessionUtility as SessionUtility;
use base\util\UserUtility;
use controller\Request;

abstract class Command {
    final function __construct() {}

    protected $request;

    protected $validationFailed;

    protected $loggedInUser;

    /**
     * @param Request $request
     */
    public final function execute(Request $request) {
        $this->request = $request;

        if(strtolower($request->getProperty("cmd")) == "game" && $request->getProperty("action") == "siteTransfer") {
            $this->doExecute($request);
        } else {
            if(strtolower($request->getProperty("cmd")) !== "logger") {
                if(SessionUtility::isLoggedIn ()) {// user logged in
                    $this->loggedInUser = UserUtility::getLoggedInUser();
                    if($this->loggedInUser === null) {// sanity check
                        $loggedOutPresenter = new LoggedOutPresenter();
                        $loggedOutPresenter->create();
                        return;
                    }
                } else {
                    $loggedOutPresenter = new LoggedOutPresenter();
                    $loggedOutPresenter->create();
                    return;
                }
                $this->doExecute($request);
            } else {
                $this->doExecute($request);
            }
        }
        return;
    }

    private function checkCookies() {
//        $cookieName = \base\ApplicationRegistry::getCookieName();
//        $cookiePass = \base\ApplicationRegistry::getCookiePass();
//
//        if(isset($_COOKIE[$cookieName]) && isset($_COOKIE[$cookiePass]))
//        {
//            $arr = array('displayName');
//            $idobj = new \mapper\IdentityObject (null, $arr);
//            $member = \domain\Member ::produceOne($idobj->field('displayName')->eq($_COOKIE[$cookieName])->field('password')->eq($_COOKIE[$cookiePass]));
//
//            if($member)
//            {
//                \base\SessionRegistry::instance()->setLoggedInStatus(true);
//                \base\SessionRegistry::instance()->setDisplayName($_COOKIE[$cookieName]);
//            }
//        }
    }

    protected abstract function doExecute(Request $request);
}
?>