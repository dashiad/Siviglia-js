<?php namespace model\reflection\Types\types;


include_once(__DIR__."/../BaseReflectedType.php");
class UserId extends \model\reflection\types\BaseReflectedType
{
    function __construct($name,$parentType=null, $value=null,$validationMode=null){
parent::__construct($name, "UserId",[
                "TYPE"=>["LABEL"=>"Type","TYPE"=>"String","FIXED"=>"UserId"]
            ],$parentType,$value,$validationMode);

    }

}
