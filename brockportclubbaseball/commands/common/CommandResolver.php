<?php
/**
 * Created by PhpStorm.
 * User: Justin
 * Date: 7/19/14
 * Time: 2:19 PM
 */

namespace commands\common;


use commands\Defaults\DefaultsCommand;

class CommandResolver {
    private static $base_cmd;
    private static $default_cmd;

    function __construct() {
        if(!self::$base_cmd) {
            self::$base_cmd = new \ReflectionClass("\commands\common\Command");
            self::$default_cmd = new DefaultsCommand();
        }
    }

    public function getCommand(\controller\Request $request) {
        // TODO: Need to take $request->getProperty("app") === "true" into consideration and in that case it's in commands/apps/{$cmd}/{$cmd}Command.php
        $cmd = $request->getProperty('cmd');// this would be c/a from dic mod rewrite thread I think...
        $sep = DIRECTORY_SEPARATOR;
        if(!$cmd) {
            return self::$default_cmd;
        }

        $cmd = str_replace(array('.', $sep), "", $cmd);// in $cmd if there's dot or a /, replace it with empty string
        $cmd = UCFirst(strtolower($cmd));
        $filepath = "commands/{$cmd}/{$cmd}Command.php";
        $classname = "\\commands\\{$cmd}\\{$cmd}Command";

        if(file_exists($filepath)) {
            require_once("$filepath");
            if(class_exists($classname)) {
                $cmd_class = new \ReflectionClass($classname);
                if($cmd_class->isSubClassOf(self::$base_cmd))
                {
                    return $cmd_class->newInstance();
                }
            }
        }
        return clone self::$default_cmd;
    }
}
?>