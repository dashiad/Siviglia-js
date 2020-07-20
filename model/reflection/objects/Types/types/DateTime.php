<?php namespace model\reflection\Types\types;

include_once(__DIR__."/../BaseReflectedType.php");
class DateTime extends \model\reflection\types\BaseReflectedType
{
    function __construct($name,$parentType=null, $value=null,$validationMode=null){
parent::__construct($name, "DateTime",[
                "TYPE"=>["LABEL"=>"Type","TYPE"=>"String","FIXED"=>"DateTime"],
                "STARTYEAR"=>["LABEL"=>"Año mínimo","TYPE"=>"Integer","KEEP_KEY_ON_EMPTY"=>false],
                "ENDYEAR"=>["LABEL"=>"Año máximo","TYPE"=>"Integer","KEEP_KEY_ON_EMPTY"=>false],
                "STRICTLYPAST"=>["LABEL"=>"Fecha debe ser pasada","TYPE"=>"Boolean","DEFAULT"=>false,"KEEP_KEY_ON_EMPTY"=>false],
                "STRICTLYFUTURE"=>["LABEL"=>"Fecha debe ser futura","TYPE"=>"Boolean","DEFAULT"=>false,"KEEP_KEY_ON_EMPTY"=>false],
                "TIMEZONE"=>["LABEL"=>"Timezone","TYPE"=>"String","DEFAULT"=>"UTC","KEEP_KEY_ON_EMPTY"=>false]
            ],$parentType,$value,$validationMode);

    }

}
