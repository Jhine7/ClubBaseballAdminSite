<?php
/**
 * Created by PhpStorm.
 * User: Justin
 * Date: 4/12/14
 * Time: 1:11 AM
 */

namespace controller;

class Request {
    private $properties;
    private $extras;
    private $feedback = array();// conduit through which controller classes can pass messages to the user

    function __construct() {
        $this->init();
        \base\RequestRegistry::setRequest($this);
    }

    // works with command line arguments as well as with HTTP requests - good for testing and debugging
    function init() {
        $this->properties = array();
        if(isset($_SERVER['REQUEST_METHOD'])) {
            $urlPieces = explode("/", $_GET['q']);
            array_shift($urlPieces);// the way our mod_rewrite does this, it adds an empty string as an extra element on the beginning
            if($urlPieces[0] === "a") {// something like foo/bar/test
                $this->setProperty("app", "true");
                $this->setProperty("cmd", $urlPieces[1]);
                $this->setProperty("action", $urlPieces[2]);
            } else {
                $this->setProperty("cmd", $urlPieces[0]);
                $this->setProperty("action", $urlPieces[1]);

                if(count($urlPieces) > 2) {
                    for($i = 2; $i < count($urlPieces); ++$i) {
                        $this->extras[] = $urlPieces[$i];
                    }
                }
            }
        }

        if(isset($_SERVER['argv'][0])) {
            $argPieces = explode("&", $_SERVER['argv'][0]);
        } else {
            $argPieces = array();
        }

        foreach($argPieces as $arg) {
            if(strpos($arg, '=')) {
                list($key, $val) = explode("=", $arg);// a=login <- a = 'cmd' in CommandResolver
                $this->setProperty($key, $val);
            }
        }
    }

    function getProperty($key) {
        if(isset($this->properties[$key])) {
            return $this->properties[$key];
        }
    }

    function setProperty($key, $val) {
        $this->properties[$key] = $val;
    }

    function getExtras() {
        return $this->extras;
    }

    function toString() {
        $v = print_r($this);
        return $v;
    }
}
?>