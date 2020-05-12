<?php namespace model\reflection\Types\types;

class Date extends \lib\model\types\Container
{
    function __construct($name,$parentType=null, $value=null,$validationMode=null){
parent::__construct($name, [
            "LABEL"=>"Date",
            "TYPE"=>"Container",
            "FIELDS"=>[
                "TYPE"=>["LABEL"=>"Type","TYPE"=>"String","FIXED"=>"Date"],
                "STARTYEAR"=>["LABEL"=>"Año mínimo","TYPE"=>"Integer","KEEP_KEY_ON_EMPTY"=>false],
                "ENDYEAR"=>["LABEL"=>"Año máximo","TYPE"=>"Integer","KEEP_KEY_ON_EMPTY"=>false],
                "STRICTLYPAST"=>["LABEL"=>"Fecha debe ser pasada","TYPE"=>"Boolean","DEFAULT"=>false,"KEEP_KEY_ON_EMPTY"=>false],
                "STRICTLYFUTURE"=>["LABEL"=>"Fecha debe ser futura","TYPE"=>"Boolean","DEFAULT"=>false,"KEEP_KEY_ON_EMPTY"=>false],
                "TIMEZONE"=>["LABEL"=>"Timezone","TYPE"=>"String","DEFAULT"=>"UTC","KEEP_KEY_ON_EMPTY"=>false],
                "HELP"=>["LABEL"=>"Ayuda","TYPE"=>"Text","KEEP_KEY_ON_EMPTY"=>false],
                "KEEP_KEY_ON_EMPTY"=>["LABEL"=>"Permitir valor vacío","TYPE"=>"Boolean","KEEP_KEY_ON_EMPTY"=>false],
                "REQUIRED"=>["TYPE"=>"Boolean","DEFAULT"=>false,"LABEL"=>"Requerido","KEEP_KEY_ON_EMPTY"=>false],
            ]
        ,$parentType,$value,$validationMode]);

    }

}
