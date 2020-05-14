<?php namespace model\reflection\Types\types;


include_once(__DIR__."/../BaseReflectedType.php");
class Label extends \model\reflection\types\BaseReflectedType
{
    function __construct($name,$parentType=null, $value=null,$validationMode=null){
parent::__construct($name, "Label",[
                "TYPE"=>["LABEL"=>"Type","TYPE"=>"String","FIXED"=>"Label"]
            ],$parentType,$value,$validationMode);

    }

}
