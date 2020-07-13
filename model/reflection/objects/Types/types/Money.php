<?php namespace model\reflection\Types\types;


include_once(__DIR__."/../BaseReflectedType.php");
class Money extends \model\reflection\types\BaseReflectedType
{
    function __construct($name,$parentType=null, $value=null,$validationMode=null){
parent::__construct($name, "Money",[
                "TYPE"=>["LABEL"=>"Type","TYPE"=>"String","FIXED"=>"Money"]
            ],$parentType,$value,$validationMode);

    }

}
