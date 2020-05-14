<?php namespace model\reflection\Types\types;


include_once(__DIR__."/../BaseReflectedType.php");
class NIF extends \model\reflection\types\BaseReflectedType
{
    function __construct($name,$parentType=null, $value=null,$validationMode=null){
parent::__construct($name, "NIF", [
                "TYPE"=>["LABEL"=>"Type","TYPE"=>"String","FIXED"=>"NIF"]
            ],$parentType,$value,$validationMode);

    }

}
