<?php namespace model\reflection\Types\types;
include_once(__DIR__."/../BaseReflectionType.php");

namespace model\reflection\Types\types;
include_once(__DIR__."/../BaseReflectionType.php");
class File extends \model\reflection\Types\BaseReflectionType
{
    function getMeta()
    {
        return [
            "TYPE" => "Container",
            "FIELDS" => [
                "TYPE" => ["TYPE" => "String", "FIXED" => "File"],
                "MINSIZE"=>["TYPE"=>"Integer","LABEL"=>"Tamaño mínimo","KEEP_KEY_ON_EMPTY"=>false],
                "MAXSIZE"=>["TYPE"=>"Integer","LABEL"=>"Tamaño máximo","KEEP_KEY_ON_EMPTY"=>false],
                "EXTENSIONS"=>["TYPE"=>"Array","LABEL"=>"Extensiones","ELEMENTS"=>["TYPE"=>"String"],"REQUIRED"=>true,"KEEP_KEY_ON_EMPTY"=>false],
                "TARGET_FILENAME"=>["TYPE"=>"String","LABEL"=>"Nombre de destino","REQUIRED"=>true],
                "TARGET_FILEPATH"=>["TYPE"=>"String","LABEL"=>"Path destino","REQUIRED"=>true],
                "PATH_TYPE" => ["TYPE"=>"Enum","LABEL"=>"Tipo de path", "VALUES"=>["ABSOLUTE","RELATIVE"],"REQUIRED"=>true],
                "AUTODELETE" => ["TYPE"=>"Boolean","LABEL"=>"Borrado automático","DEFAULT"=>false],
                "HELP" => ["LABEL" => "Ayuda", "TYPE" => "Text", "KEEP_KEY_ON_EMPTY" => false],
                "KEEP_KEY_ON_EMPTY" => ["LABEL" => "Permitir valor vacío", "TYPE" => "Boolean", "KEEP_KEY_ON_EMPTY" => false],
                "REQUIRED" => ["TYPE" => "Boolean", "DEFAULT" => false, "LABEL" => "Requerido", "KEEP_KEY_ON_EMPTY" => false]
            ]
        ];
    }

}
