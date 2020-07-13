<?php namespace model\reflection\Types\types;


include_once(__DIR__."/../BaseReflectedType.php");
class Phone extends \model\reflection\types\BaseReflectedType
{
    function __construct($name,$parentType=null, $value=null,$validationMode=null){
parent::__construct($name, "Phone",[
                "TYPE"=>["LABEL"=>"Type","TYPE"=>"String","FIXED"=>"Phone"]
            ],$parentType,$value,$validationMode);

    }

}
