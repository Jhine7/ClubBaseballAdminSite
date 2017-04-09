<?php
/**
 * Created by PhpStorm.
 * User: Justin
 * Date: 4/12/14
 * Time: 1:20 AM
 */

namespace base;

class SessionRegistry extends Registry {
    private $values = array();
    private static $instance;

    private function __construct() {
        session_start();
    }

    static function instance() {
        if(!isset(self::$instance))
        {
            self::$instance = new self();
        }
        return self::$instance;
    }

    function get($key) {
        if(isset($_SESSION[__CLASS__][$key]))
        {
            return $_SESSION[__CLASS__][$key];
        }
        return null;
    }

    function set($key, $val) {
        $_SESSION[__CLASS__][$key] = $val;
    }

    function un_set($key) {
        unset($_SESSION[__CLASS__][$key]);
    }

    function setComplex(Complex $complex)  {
        self::instance()->set('complex', $complex);
    }

    function getComplex() {
        return self::instance()->get('complex');
    }

    function setLoggedInStatus($loggedIn) {
        self::instance()->set('loggedIn', $loggedIn);
    }

    function getLoggedInStatus() {
        return self::instance()->get('loggedIn');
    }

    function setFBAccessToken($token) {
        self::instance()->set('fBAccess', $token);
    }

    function getFBAccessToken() {
        return self::instance()->get('fBAccess');
    }

    function setUserId($id)
    {
        self::instance()->set('userId', $id);
    }

    function getUserId()
    {
        return self::instance()->get('userId');
    }

    function unsetUserId()
    {
        self::instance()->un_set('userId');
    }

    function setEmail($email)
    {
        self::instance()->set('email', $email);
    }

    function getEmail()
    {
        return self::instance()->get('email');
    }

    function unsetEmail()
    {
        self::instance()->un_set('email');
    }

    function setName($name) {
        self::instance()->set('name', $name);
    }

    function getName() {
        return self::instance()->get('name');
    }

    function unsetName() {
        self::instance()->un_set('name');
    }

    function setAdminId($id) {
        self::instance()->set('adminId', $id);
    }

    function getAdminId() {
        return self::instance()->get('adminId');
    }

    function unsetAdminId() {
        self::instance()->un_set('adminId');
    }
}
?>