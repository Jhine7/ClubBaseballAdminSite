<?php
/**
 * Created by PhpStorm.
 * User: Justin
 * Date: 7/19/14
 * Time: 1:53 PM
 */
class Foo {
    static public function test($classname)
    {
        if(preg_match('/\\\\/', $classname))
        {
            $path = str_replace('\\', '/', $classname);
        }
        else
        {
            $path = str_replace('_', '/', $classname);
        }

        if (is_readable($path.".php"))
        {
            require_once $path .".php";
        }
    }
}
spl_autoload_register('\Foo::test');

\controller\Controller::run();
?>