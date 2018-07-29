<?php
/**
 * Created by PhpStorm.
 * User: Usuario
 * Date: 27/02/2018
 * Time: 17:01
 */

namespace model\reflection\Model\autoui;

include_once(PROJECTPATH."/model/reflection/objects/base/AutoUIDefinition.php");
class Fields extends \model\reflection\base\AutoUIDefinition
{
    static $definition = array(
        "IMPORT"=>["Model|Types"],
        "DEFINITION" => array(
            "ROOT" => array(
                "TYPE" => "DICTIONARY",
                "LABEL" => "Fields",
                "VALUETYPE"=>"DataTypeSwitcher"
            )
        )
    );

}
