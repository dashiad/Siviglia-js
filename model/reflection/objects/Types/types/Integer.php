<?php namespace model\reflection\Types\types;


include_once(__DIR__."/../BaseReflectedType.php");
class Integer extends \model\reflection\types\BaseReflectedType
{
    function __construct($name,$parentType=null, $value=null,$validationMode=null){
parent::__construct($name,"Integer",
            [
                "TYPE"=>["LABEL"=>"Type","TYPE"=>"String","FIXED"=>"Integer"],
                "MIN"=>["TYPE"=>"Integer","LABEL"=>"Valor mínimo","KEEP_KEY_ON_EMPTY"=>false],
                "MAX"=>["TYPE"=>"Integer","LABEL"=>"Valor máximo","KEEP_KEY_ON_EMPTY"=>false]
            ],$parentType,$value,$validationMode);

    }

}
