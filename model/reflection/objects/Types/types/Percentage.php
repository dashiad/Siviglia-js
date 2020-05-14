<?php namespace model\reflection\Types\types;


include_once(__DIR__."/../BaseReflectedType.php");
class Percentage extends \model\reflection\types\BaseReflectedType
{
    function __construct($name,$parentType=null, $value=null,$validationMode=null){
parent::__construct($name, "Percentage",[
                "TYPE"=>["LABEL"=>"Type","TYPE"=>"String","FIXED"=>"Percentage"]
            ],$parentType,$value,$validationMode);

    }

}
