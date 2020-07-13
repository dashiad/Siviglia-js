<?php namespace model\reflection\Types\types;


include_once(__DIR__."/../BaseReflectedType.php");
class Boolean extends \model\reflection\types\BaseReflectedType
{
    function __construct($name,$parentType=null, $value=null,$validationMode=null){
parent::__construct($name, "Boolean",[
                "TYPE"=>["LABEL"=>"Type","TYPE"=>"String","FIXED"=>"Boolean"],
                "DEFAULT"=>["LABEL"=>"Valor por defecto","TYPE"=>"Boolean","KEEP_ON_EMPTY"=>false]
            ],$parentType,$value,$validationMode);

    }

}
