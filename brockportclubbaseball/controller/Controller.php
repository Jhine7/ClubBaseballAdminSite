<?php
/**
 * Created by PhpStorm.
 * User: Justin
 * Date: 4/12/14
 * Time: 12:54 AM
 */
namespace controller;

use base\Registry;

class Controller {
    private $applicationHelper;
    private $template;

    protected function __construct() {}

    static function run() {
        $instance = new Controller();
        $instance->init();
        $instance->handleRequest();
    }

    // could possibly be changed to only run at start up and handleRequest is run for each user request
    function init() {
        $applicationHelper = ApplicationHelper::instance();
        $applicationHelper->init();
    }

    // way of deciding how to interpret HTTP request so that it can invoke the right code to fulfill the request
    function handleRequest() {
        $request = new \controller\Request();
        $cmd_r = new \commands\common\CommandResolver();
        $cmd = $cmd_r->getCommand($request);
        Registry::createUnitOfWork();

        $presenter = $cmd->execute($request);
        Registry::destroyUnitOfWork();
    }
}
?>