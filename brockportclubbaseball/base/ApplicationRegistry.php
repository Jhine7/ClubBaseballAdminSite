<?php
/**
 * Created by PhpStorm.
 * User: Justin
 * Date: 4/12/14
 * Time: 12:58 AM
 */

namespace base;


class ApplicationRegistry {
    private static $instance;

    private static $PDO;

    private function __construct() {
        self::$PDO = $db = new \PDO('mysql:unix_socket=/cloudsql/brockportclubbaseballstats:brockportclubbaseballstats;dbname=brockportclubbaseballstats;charset=utf8','root','');
//        self::$PDO = $db = new \PDO('mysql:host=173.194.242.206;dbname=brockportclubbaseballstats;charset=utf8','root','shstrack2');
        self::$PDO->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }

    static function instance() {
        if(!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    static function getDSN() {
        if(!isset(self::$PDO)) {
            self::instance();
            return self::$PDO;
        }
        return self::$PDO;
    }
}