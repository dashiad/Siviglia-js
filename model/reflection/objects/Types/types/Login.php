<?php namespace model\reflection\Types\types;


include_once(__DIR__."/../BaseReflectedType.php");
class Login extends \model\reflection\types\BaseReflectedType
{
    function __construct($name,$parentType=null, $value=null,$validationMode=null){
parent::__construct($name,"Login", [
                "TYPE"=>["LABEL"=>"Type","TYPE"=>"String","FIXED"=>"Login"]
            ],$parentType,$value,$validationMode);

    }

}
