<?php namespace model\reflection\Types\types;

include_once(__DIR__."/../BaseReflectedType.php");
class Description extends \model\reflection\types\BaseReflectedType
{
    function __construct($name,$parentType=null, $value=null,$validationMode=null){
parent::__construct($name, "Description",[
                "TYPE"=>["LABEL"=>"Type","TYPE"=>"String","FIXED"=>"Description"],
                "DEFAULT"=>["TYPE"=>"String","LABEL"=>"Valor por defecto","KEEP_KEY_ON_EMPTY"=>false]
            ],$parentType,$value,$validationMode);

    }

}
