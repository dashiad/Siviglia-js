<?php namespace model\reflection\Types\types;


include_once(__DIR__."/../BaseReflectedType.php");
class UUID extends \model\reflection\types\BaseReflectedType
{
    function __construct($name,$parentType=null, $value=null,$validationMode=null){
parent::__construct($name, "UUID",[
                "TYPE"=>["LABEL"=>"Type","TYPE"=>"String","FIXED"=>"UUID"],
                "LEVEL"=>["TYPE"=>"Enum","LABEL"=>"Level","VALUES"=>[1,3,4,5],"DEFAULT"=>1]
            ],$parentType,$value,$validationMode);

    }

}
