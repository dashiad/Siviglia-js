<?php
namespace model\reflection\Types\types;

include_once(__DIR__."/../BaseReflectedType.php");
class UrlPathString extends \model\reflection\types\BaseReflectedType
{
    function __construct($name,$parentType=null, $value=null,$validationMode=null){
parent::__construct($name, "UrlPathString", [
                "TYPE" => ["LABEL"=>"TYPE","TYPE" => "String", "FIXED" => "UrlPathString"]
            ],$parentType,$value,$validationMode);

    }

}
