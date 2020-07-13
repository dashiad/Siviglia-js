<?php namespace model\reflection\Types\types;


include_once(__DIR__."/../BaseReflectedType.php");
class Color extends \model\reflection\types\BaseReflectedType
{
    function __construct($name,$parentType=null, $value=null,$validationMode=null){
parent::__construct($name, "Color",[
                "TYPE"=>["LABEL"=>"Type","TYPE"=>"String","FIXED"=>"Color"],
                "DEFAULT"=>["TYPE"=>"Color","LABEL"=>"Valor por defecto","KEEP_KEY_ON_EMPTY"=>false]
            ],$parentType,$value,$validationMode);

    }

}
