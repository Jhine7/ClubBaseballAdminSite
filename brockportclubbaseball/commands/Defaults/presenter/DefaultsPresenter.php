<?php
/**
 * Created by PhpStorm.
 * User: Justin
 * Date: 7/19/14
 * Time: 2:40 PM
 */

namespace commands\Defaults\presenter;


use commands\common\IPresenter;
use commands\Defaults\view\HomeView;

class DefaultsPresenter implements IPresenter {

    function __construct() {

    }

    function create() {
        $homeView = new HomeView();

        echo $homeView->create();
    }
}
?>