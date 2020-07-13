<?php namespace model\reflection\Types\types;


namespace model\reflection\Types\types;

include_once(__DIR__."/../BaseReflectedType.php");
class File extends \model\reflection\types\BaseReflectedType
{
    function __construct($name,$parentType=null, $value=null,$validationMode=null){
parent::__construct($name, "File", [
                "TYPE" => ["LABEL"=>"Tipo","TYPE" => "String", "FIXED" => "File"],
                "MINSIZE"=>["TYPE"=>"Integer","LABEL"=>"Tamaño mínimo","KEEP_KEY_ON_EMPTY"=>false],
                "MAXSIZE"=>["TYPE"=>"Integer","LABEL"=>"Tamaño máximo","KEEP_KEY_ON_EMPTY"=>false],
                "EXTENSIONS"=>["TYPE"=>"Array","LABEL"=>"Extensiones","ELEMENTS"=>["TYPE"=>"String"],"REQUIRED"=>true,"KEEP_KEY_ON_EMPTY"=>false],
                "TARGET_FILENAME"=>["TYPE"=>"String","LABEL"=>"Nombre de destino","REQUIRED"=>true],
                "TARGET_FILEPATH"=>["TYPE"=>"String","LABEL"=>"Path destino","REQUIRED"=>true],
                "PATH_TYPE" => ["TYPE"=>"Enum","LABEL"=>"Tipo de path", "VALUES"=>["ABSOLUTE","RELATIVE"],"REQUIRED"=>true],
                "AUTODELETE" => ["TYPE"=>"Boolean","LABEL"=>"Borrado automático","DEFAULT"=>false]
            ],$parentType,$value,$validationMode);

    }

}
