<?php namespace model\reflection\Types\types;

include_once(__DIR__."/../BaseReflectedType.php");
class Timestamp extends \model\reflection\types\BaseReflectedType
{
    function __construct($name,$parentType=null, $value=null,$validationMode=null){
parent::__construct($name, "Timestamp",[
                "TYPE"=>["LABEL"=>"Type","TYPE"=>"String","FIXED"=>"Timestamp"]
            ],$parentType,$value,$validationMode);

    }

}
