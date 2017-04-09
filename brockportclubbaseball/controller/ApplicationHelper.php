<?php
/**
 * Created by PhpStorm.
 * User: Justin
 * Date: 4/12/14
 * Time: 12:56 AM
 */

namespace controller;


class ApplicationHelper {
    private static $instance;
    private $config = "tmp/data/web_options.xml";

    private function __construct() {}

    static function instance() {
        if(!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    function init() {
        $this->getOptions();
//        $dsn = \base\ApplicationRegistry::getDSN();
//        $cookieName = \base\ApplicationRegistry::getCookieName();
//        $cookiePass = \base\ApplicationRegistry::getCookiePass();
//        if($dsn && $cookieName && $cookiePass) {
//            return;// if the Registry object is already populated, init() does nothing
//        }
    }

    // reads configuration files and makes values available to clients
    private function getOptions() {
        \base\ApplicationRegistry::getDSN();

        // set other values... -- only place in code where we should be using set functions
    }

    private function ensure($expr, $message) {
        if(!$expr) {
            throw new \Exception($message);
        }
    }
} 