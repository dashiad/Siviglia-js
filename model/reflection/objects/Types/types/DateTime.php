<?php namespace model\reflection\Types\types;
include_once(__DIR__."/../BaseReflectionType.php");
class DateTime extends \model\reflection\Types\BaseReflectionType
{
    function getMeta()
    {
        return [
            "TYPE"=>"Container",
            "FIELDS"=>[
                "TYPE"=>["TYPE"=>"String","FIXED"=>"DateTime"],
                "STARTYEAR"=>["LABEL"=>"Año mínimo","TYPE"=>"Integer","KEEP_KEY_ON_EMPTY"=>false],
                "ENDYEAR"=>["LABEL"=>"Año máximo","TYPE"=>"Integer","KEEP_KEY_ON_EMPTY"=>false],
                "STRICTLYPAST"=>["LABEL"=>"Fecha debe ser pasada","TYPE"=>"Boolean","DEFAULT"=>false,"KEEP_KEY_ON_EMPTY"=>false],
                "STRICTLYFUTURE"=>["LABEL"=>"Fecha debe ser futura","TYPE"=>"Boolean","DEFAULT"=>false,"KEEP_KEY_ON_EMPTY"=>false],
                "TIMEZONE"=>["LABEL"=>"Timezone","TYPE"=>"String","DEFAULT"=>"UTC","KEEP_KEY_ON_EMPTY"=>false],
                "HELP"=>["LABEL"=>"Ayuda","TYPE"=>"Text","KEEP_KEY_ON_EMPTY"=>false],
                "KEEP_KEY_ON_EMPTY"=>["LABEL"=>"Permitir valor vacío","TYPE"=>"Boolean","KEEP_KEY_ON_EMPTY"=>false],
                "REQUIRED"=>["TYPE"=>"Boolean","DEFAULT"=>false,"LABEL"=>"Requerido","KEEP_KEY_ON_EMPTY"=>false],
            ]
        ];
    }

}
