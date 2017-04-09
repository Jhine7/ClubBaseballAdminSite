<?php
/**
 * Created by PhpStorm.
 * User: Justin
 * Date: 7/19/14
 * Time: 2:05 PM
 */

namespace commands\Defaults;


use commands\common\Command;
use commands\Defaults\presenter\DefaultsPresenter;
use controller\Request;

class DefaultsCommand extends Command {

    protected function doExecute(Request $request) {
        $defaultsPresenter = new DefaultsPresenter();
        $defaultsPresenter->create();
    }
}
?>