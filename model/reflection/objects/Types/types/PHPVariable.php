<?php namespace model\reflection\Types\types;


include_once(__DIR__."/../BaseReflectedType.php");
class PHPVariable extends \model\reflection\types\BaseReflectedType
{
    function __construct($name,$parentType=null, $value=null,$validationMode=null){
parent::__construct($name, "PHPVariable", [
                "TYPE"=>["LABEL"=>"Type","TYPE"=>"String","FIXED"=>"PHPVariable"]
            ],$parentType,$value,$validationMode);

    }

}
