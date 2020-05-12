<?php namespace model\reflection\Types\types;


class Password extends \lib\model\types\Container
{
    function __construct($name,$parentType=null, $value=null,$validationMode=null){
parent::__construct($name, [
            "TYPE"=>"Container",
            "FIELDS"=>[
                "TYPE"=>["LABEL"=>"Type","TYPE"=>"String","FIXED"=>"Password"],
                "PASSWORD_ENCODING"=>["TYPE"=>"String","LABEL"=>"Codificación","DEFAULT"=>"BCRYPT","REQUIRED"=>true],
                "COST"=>["TYPE"=>"Integer","LABEL"=>"Coste","REQUIRED"=>true],
                "HELP"=>["LABEL"=>"Ayuda","TYPE"=>"Text","KEEP_KEY_ON_EMPTY"=>false],
                "KEEP_KEY_ON_EMPTY"=>["LABEL"=>"Permitir valor vacío","TYPE"=>"Boolean","KEEP_KEY_ON_EMPTY"=>false],
                "REQUIRED"=>["TYPE"=>"Boolean","DEFAULT"=>false,"LABEL"=>"Requerido","KEEP_KEY_ON_EMPTY"=>false],
                "DEFAULT"=>["TYPE"=>"String","LABEL"=>"Valor por defecto","KEEP_KEY_ON_EMPTY"=>false],
                "SOURCE"=>\model\reflection\Types::getSourceMeta()
            ]
        ,$parentType,$value,$validationMode]);

    }

}
