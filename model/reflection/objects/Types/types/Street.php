<?php namespace model\reflection\Types\types;


include_once(__DIR__."/../BaseReflectedType.php");
class Street extends \model\reflection\types\BaseReflectedType
{
    function __construct($name,$parentType=null, $value=null,$validationMode=null){
parent::__construct($name, "Street",[
                "TYPE"=>["LABEL"=>"Type","TYPE"=>"String","FIXED"=>"Street"]
            ],$parentType,$value,$validationMode);

    }

}
