<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Justin
 * Date: 5/25/13
 * Time: 10:40 PM
 * To change this template use File | Settings | File Templates.
 */

class GenerateDomain extends Task {

    /**
     * The name passed in the buildfile.
     */
    private $name = null;

    /**
     * The setter for the attribute "name"
     */
    public function setName($val) {
        $this->name = $val;
    }

    /**
     * The init method: Do init steps.
     */
    public function init() {
        // nothing to do here
    }

    /**
     * The main entry point method.
     */
    public function main() {
        $nameArr = explode(".", $this->name);
        $name = $nameArr[0];
        $xml = SimpleXml_load_file("../domain/base/".$name.".xml");

        $path = $xml->path["name"];

        $template = file_get_contents("generateDomain/DomainObjectTemplate");
        $vars = array();
        $vars["class_name"] = "Base".ucfirst($name);
        $template = $this->replaceTokens($vars, $template);

//        print_r($xml);
        if($xml->parentClass) {
            $template = $this->replaceToken("parent_class", $xml->parentClass["name"], $template);
            $template = $this->replaceToken("super_call", file_get_contents("generateDomain/SuperCallTemplate"), $template);
            $template = $this->replaceToken("register_new", "", $template);
        } else {
            $template = $this->replaceToken("parent_class", "\\domain\\DomainObject", $template);
            $template = $this->replaceToken("super_call", "", $template);
            $template = $this->replaceToken("register_new", file_get_contents("generateDomain/RegisterNewTemplate"), $template);
        }

        $constantsPiece = "";
        $addAttributePiece = "";
        $gettersAndSettersPiece = "";
        foreach($xml->attributes->attribute as $attr) {
            // constants
            $constantsPiece.= 'const '. strtoupper($attr["name"]).  ' = "'.$attr["name"].'";
    ' ;

            // AddAttributes method
            $vars = array();
            $vars["class_name"] = "Base".ucfirst($name);
            $vars["constant"] = strtoupper($attr["name"]);

            $attrTemplate = file_get_contents("generateDomain/AddAttributesTemplate");
            $addAttributePiece .=  $this->replaceTokens($vars, $attrTemplate);

            // getters and setters
            $vars = array();
            $vars["class_name"] = "Base".ucfirst($name);
            $vars["constant"] = strtoupper($attr["name"]);
            $vars["constant_name"] = ucfirst($attr["name"]);
            $vars["constant_normal_case"] = $attr["name"];

            $getterSetterTemplate = file_get_contents("generateDomain/GettersAndSettersTemplate");
            $gettersAndSettersPiece .=  $this->replaceTokens($vars, $getterSetterTemplate);
        }
        $template = $this->replaceToken("constants", $constantsPiece, $template);
        $template = $this->replaceToken("attributes_to_add", $addAttributePiece, $template);
        $template = $this->replaceToken("getters_and_setters", $gettersAndSettersPiece, $template);


        if($xml->mapper) {
            $mapper = $xml->mapper["name"];
            $klass = $xml->mapper["class"];
            $table = $xml->mapper["table"];
            $idField = $xml->mapper["idField"];



            if($mapper !== null && $klass !== null && $table !== null && $idField !== null) {
                $t = file_get_contents("generateDomain/MapperTemplate");
                $vars = array();
                $vars["class_name"] = $klass;
                $vars["table_name"] = $table;
                $vars["id_field"] = $idField;
                echo "MADE IT HERE!";
                print_r($xml->mapper->joins);

                if($xml->mapper->joins) {
                    echo "Had Joins!";
                    $joinsTemplate = file_get_contents("generateDomain/MapperJoinTemplate");
                    $joinsPiece = "";
                    foreach($xml->mapper->joins->join as $join) {

                        $joinVars = array();
                        $joinVars["join_name"] = $join["name"];
                        $joinVars["join_table"] = $join["table"];
                        $joinsPiece .= $this->replaceTokens($joinVars, $joinsTemplate);
                    }
                    $vars["joins"] = $joinsPiece;
                } else {
                    $vars["joins"] = "";
                }


                $t = $this->replaceTokens($vars, $t);

                if(file_exists("../mapper/".$mapper.".php")) {
                    $mapperContent = file_get_contents("../mapper/".$mapper.".php");
                    if(preg_match('@(p)ublic function loadDataMap\(\) {[\s\S]*?(})@i', $mapperContent, $matches, PREG_OFFSET_CAPTURE)) {
                        $mapperContent = substr_replace($mapperContent, $t, $matches[1][1], ($matches[2][1] - $matches[1][1]) + 1);

                        $fh = fopen("../mapper/".$mapper.".php", "w");
                        fwrite($fh, $mapperContent);
                        fclose($fh);
                    } else {
                        if(preg_match('@class\s*'.$klass.'[\s\S]*(})[\s\S]*\?>@i', $mapperContent, $matches, PREG_OFFSET_CAPTURE)) {
                            $mapperContent = substr_replace($mapperContent, $t, $matches[1][1] - 1, 0);

                            $fh = fopen("../mapper/".$mapper.".php", "w");
                            fwrite($fh, $mapperContent);
                            fclose($fh);
                        } else {
                            throw new BuildException("Could not match regular expression in: ".$mapper);
                        }
                    }



                } else {
                    throw new BuildException("Mapper file did not exist ". $mapper);
                }
            }
        }


        $fh = fopen("../domain/base/"."Base".ucfirst($name).".php", "w");
        fwrite($fh, $template);
        fclose($fh);
    }

    private function replaceTokens($tokens, $template) {
        foreach($tokens as $n => $value) {
            $template = $this->replaceToken($n, $value, $template);
        }
        return $template;
    }

    private function replaceToken($token, $replace, $template) {
        $template = str_replace('{{' . strtoupper($token) . '}}', $replace, $template);
        return $template;
    }
}
?>