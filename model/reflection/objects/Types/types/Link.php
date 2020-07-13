<?php namespace model\reflection\Types\types;


include_once(__DIR__."/../BaseReflectedType.php");
class Link extends \model\reflection\types\BaseReflectedType
{
    function __construct($name,$parentType=null, $value=null,$validationMode=null){
parent::__construct($name, "Link",[
                "TYPE"=>["LABEL"=>"Type","TYPE"=>"String","FIXED"=>"Link"]
            ],$parentType,$value,$validationMode);

    }

}
