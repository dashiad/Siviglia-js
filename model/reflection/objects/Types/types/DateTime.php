<?php namespace model\reflection\Types\types;

class DateTime extends \lib\model\types\Container
{
    function __construct(){
parent::__construct( [
            "LABEL"=>"DateTime",
            "TYPE"=>"Container",
            "FIELDS"=>[
                "TYPE"=>["LABEL"=>"Type","TYPE"=>"String","FIXED"=>"DateTime"],
                "STARTYEAR"=>["LABEL"=>"Año mínimo","TYPE"=>"Integer","KEEP_KEY_ON_EMPTY"=>false],
                "ENDYEAR"=>["LABEL"=>"Año máximo","TYPE"=>"Integer","KEEP_KEY_ON_EMPTY"=>false],
                "STRICTLYPAST"=>["LABEL"=>"Fecha debe ser pasada","TYPE"=>"Boolean","DEFAULT"=>false,"KEEP_KEY_ON_EMPTY"=>false],
                "STRICTLYFUTURE"=>["LABEL"=>"Fecha debe ser futura","TYPE"=>"Boolean","DEFAULT"=>false,"KEEP_KEY_ON_EMPTY"=>false],
                "TIMEZONE"=>["LABEL"=>"Timezone","TYPE"=>"String","DEFAULT"=>"UTC","KEEP_KEY_ON_EMPTY"=>false],
                "HELP"=>["LABEL"=>"Ayuda","TYPE"=>"Text","KEEP_KEY_ON_EMPTY"=>false],
                "KEEP_KEY_ON_EMPTY"=>["LABEL"=>"Permitir valor vacío","TYPE"=>"Boolean","KEEP_KEY_ON_EMPTY"=>false],
                "REQUIRED"=>["TYPE"=>"Boolean","DEFAULT"=>false,"LABEL"=>"Requerido","KEEP_KEY_ON_EMPTY"=>false],
            ]
        ]);

    }

}
