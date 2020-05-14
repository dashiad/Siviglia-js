<?php namespace model\reflection\Types\types;

include_once(__DIR__."/../BaseReflectedType.php");
class TreePath extends \model\reflection\types\BaseReflectedType
{
    function __construct($name,$parentType=null, $value=null,$validationMode=null){
parent::__construct($name, "TreePath",[
                "TYPE"=>["LABEL"=>"Type","TYPE"=>"String","FIXED"=>"TreePath"]
            ],$parentType,$value,$validationMode);

    }

}
