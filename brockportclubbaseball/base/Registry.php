<?php
/**
 * Created by PhpStorm.
 * User: Justin
 * Date: 4/12/14
 * Time: 1:22 AM
 */

namespace base;

use domain\AdminRepository;
use domain\FeedItemRepository;
use domain\GameRepository;
use domain\InviteCommentRepository;
use domain\InviteRepository;
use domain\LocationRepository;
use domain\PlayerRepository;
use domain\PrivateMessageRepository;
use domain\ReviewRepository;
use domain\UserRepository;

class Registry {
    private static $instance;
    private $_unitOfWork = null;
    private static $_createdMappers = array();

    private function __construct(){}// singleton

    static function instance() {
        if(!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public static function createUnitOfWork() {
        $inst = self::instance();
        if($inst->_unitOfWork === null) {
            $inst->_unitOfWork = new UnitOfWork();
        }
    }

    /**
     * @return \base\UnitOfWork
     */
    public static function getUnitOfWork() {
        $inst = self::instance();
        return $inst->_unitOfWork;
    }

    public static function destroyUnitOfWork() {
        $inst = self::instance();
        if($inst->_unitOfWork->commit()) {// commit before destroying
            $inst->_unitOfWork = null;
            return true;// commit went through with no errors
        }
        return false;
    }

    /**
     * @param string $klass
     * @return \mapper\Mapper
     */
    public static function getMapper($klass) {
        // TODO: If reusing the same Mapper for Objects of the same type breaks stuff, take out $_createdMappers logic
        if(isset(self::$_createdMappers[$klass])) {
            return self::$_createdMappers[$klass];
        } else {
            $mapperClass = new \ReflectionClass("\mapper\\".$klass."Mapper");
            $mapperInstance = $mapperClass->newInstance();
            self::$_createdMappers[$klass] = $mapperInstance;
            return $mapperInstance;
        }
    }

    public static function gameRepository() {
        return new GameRepository();
    }

    public static function playerRepository() {
        return new PlayerRepository();
    }

    public static function adminRepository() {
        return new AdminRepository();
    }
}
?>