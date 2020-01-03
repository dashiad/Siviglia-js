<?php namespace model\reflection\Types\meta;
class Date extends \model\reflection\Meta\BaseMetadata
{
    function getMeta()
    {
        return [
            "TYPE"=>"Container",
            "FIELDS"=>[
                "TYPE"=>["TYPE"=>"String","FIXED"=>"Date"],
                "STARTYEAR"=>["LABEL"=>"Año mínimo","TYPE"=>"Integer","SET_ON_EMPTY"=>false],
                "ENDYEAR"=>["LABEL"=>"Año máximo","TYPE"=>"Integer","SET_ON_EMPTY"=>false],
                "STRICTLYPAST"=>["LABEL"=>"Fecha debe ser pasada","TYPE"=>"Boolean","DEFAULT"=>false,"SET_ON_EMPTY"=>false],
                "STRICTLYFUTURE"=>["LABEL"=>"Fecha debe ser futura","TYPE"=>"Boolean","DEFAULT"=>false,"SET_ON_EMPTY"=>false],
                "TIMEZONE"=>["LABEL"=>"Timezone","TYPE"=>"String","DEFAULT"=>"UTC","SET_ON_EMPTY"=>false],
                "HELP"=>["LABEL"=>"Ayuda","TYPE"=>"Text","SET_ON_EMPTY"=>false],
                "SET_ON_EMPTY"=>["LABEL"=>"Permitir valor vacío","TYPE"=>"Boolean","SET_ON_EMPTY"=>false],
                "REQUIRED"=>["TYPE"=>"Boolean","DEFAULT"=>false,"LABEL"=>"Requerido","SET_ON_EMPTY"=>false],
            ]
        ];
    }

}
