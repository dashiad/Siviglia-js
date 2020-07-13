<?php namespace model\reflection\Types\types;


include_once(__DIR__."/../BaseReflectedType.php");
class Text extends \model\reflection\types\BaseReflectedType
{
    function __construct($name,$parentType=null, $value=null,$validationMode=null){
parent::__construct($name, "Text", [
                "TYPE"=>["LABEL"=>"Type","TYPE"=>"String","FIXED"=>"Text"]
            ],$parentType,$value,$validationMode);

    }

}
