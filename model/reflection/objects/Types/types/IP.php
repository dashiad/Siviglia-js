<?php namespace model\reflection\Types\types;


include_once(__DIR__."/../BaseReflectedType.php");
class IP extends \model\reflection\types\BaseReflectedType
{
    function __construct($name,$parentType=null, $value=null,$validationMode=null){
parent::__construct($name, "IP",[
                "TYPE"=>["LABEL"=>"Type","TYPE"=>"String","FIXED"=>"IP"]
            ],$parentType,$value,$validationMode);

    }

}
