<?php namespace model\reflection\Types\types;


include_once(__DIR__."/../BaseReflectedType.php");
class Password extends \model\reflection\types\BaseReflectedType
{
    function __construct($name,$parentType=null, $value=null,$validationMode=null){
parent::__construct($name,"Password", [
                "TYPE"=>["LABEL"=>"Type","TYPE"=>"String","FIXED"=>"Password"],
                "PASSWORD_ENCODING"=>["TYPE"=>"String","LABEL"=>"Codificación","DEFAULT"=>"BCRYPT","REQUIRED"=>true],
                "COST"=>["TYPE"=>"Integer","LABEL"=>"Coste","REQUIRED"=>true]
            ],$parentType,$value,$validationMode);

    }

}
