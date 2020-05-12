<?php namespace model\reflection\Types\types;


namespace model\reflection\Types\types;

class Image extends \lib\model\types\Container
{
    function __construct($name,$parentType=null, $value=null,$validationMode=null){
parent::__construct($name, [
            "LABEL"=>"Image",
            "TYPE" => "Container",
            "FIELDS" => [
                "TYPE" => ["TYPE" => "String", "FIXED" => "Image"],
                "MINSIZE"=>["TYPE"=>"Integer","LABEL"=>"Tamaño mínimo","KEEP_KEY_ON_EMPTY"=>false],
                "MAXSIZE"=>["TYPE"=>"Integer","LABEL"=>"Tamaño máximo","KEEP_KEY_ON_EMPTY"=>false],
                "EXTENSIONS"=>["TYPE"=>"Array","LABEL"=>"Extensiones","ELEMENTS"=>["TYPE"=>"String"],"REQUIRED"=>true,"KEEP_KEY_ON_EMPTY"=>false],
                "TARGET_FILENAME"=>["TYPE"=>"String","LABEL"=>"Nombre de destino","REQUIRED"=>true],
                "TARGET_FILEPATH"=>["TYPE"=>"String","LABEL"=>"Path destino","REQUIRED"=>true],
                "PATH_TYPE" => ["TYPE"=>"Enum","LABEL"=>"Tipo de path", "VALUES"=>["ABSOLUTE","RELATIVE"],"REQUIRED"=>true],
                "MINWIDTH"=>["TYPE"=>"Integer","LABEL"=>"Ancho mínimo","REQUIRED"=>false,"KEEP_KEY_ON_EMPTY"=>false],
                "MAXWIDTH"=>["TYPE"=>"Integer","LABEL"=>"Ancho máximo","REQUIRED"=>false,"KEEP_KEY_ON_EMPTY"=>false],
                "MINHEIGHT"=>["TYPE"=>"Integer","LABEL"=>"Altura mínima","REQUIRED"=>false,"KEEP_KEY_ON_EMPTY"=>false],
                "MAXHEIGHT"=>["TYPE"=>"Integer","LABEL"=>"Altura máxima","REQUIRED"=>false,"KEEP_KEY_ON_EMPTY"=>false],
                "THUMBNAIL"=>["TYPE"=>"Container","LABEL"=>"Thumbnail","REQUIRED"=>false,"KEEP_KEY_ON_EMPTY"=>false,
                    "FIELDS"=>[
                        "ENABLED"=>["TYPE"=>"Boolean","LABEL"=>"Crear thumbnail","DEFAULT"=>false,"KEEP_KEY_ON_EMPTY"=>false],
                        "WIDTH"=>["TYPE"=>"Integer","LABEL"=>"Ancho mínimo","REQUIRED"=>false,"KEEP_KEY_ON_EMPTY"=>false],
                        "HEIGHT"=>["TYPE"=>"Integer","LABEL"=>"Ancho mínimo","REQUIRED"=>false,"KEEP_KEY_ON_EMPTY"=>false],
                        "PREFIX"=>["TYPE"=>"String","LABEL"=>"Prefijo","REQUIRED"=>true],
                        "QUALITY"=>["TYPE"=>"Percentage","LABEL"=>"Calidad"],
                        "KEEPASPECT"=>["TYPE"=>"Boolean","LABEL"=>"Mantener aspecto","DEFAULT"=>true]
                        ]
                ],
                "WATERMARK"=>["TYPE"=>"Container","LABEL"=>"Watermark","REQUIRED"=>false,"KEEP_KEY_ON_EMPTY"=>false,
                    "FIELDS"=>[
                        "ENABLED"=>["TYPE"=>"Boolean","LABEL"=>"Añadir watermark","DEFAULT"=>false,"KEEP_KEY_ON_EMPTY"=>false],
                        "POSITION"=>["TYPE"=>"Enum","LABEL"=>"Posición","VALUES"=>["NW","NE","SE","SW","CENTER"]],
                        "FILE"=>["TYPE"=>"String","LABEL"=>"Fichero watermark"]
                        ]
                ],
                "DESCRIPTION"=>["TYPE"=>"Text","LABEL"=>"Descripción","REQUIRED"=>false,"KEEP_KEY_ON_EMPTY"=>false],
                "AUTODELETE" => ["TYPE"=>"Boolean","LABEL"=>"Borrado automático","DEFAULT"=>false],
                "HELP" => ["LABEL" => "Ayuda", "TYPE" => "Text", "KEEP_KEY_ON_EMPTY" => false],
                "KEEP_KEY_ON_EMPTY" => ["LABEL" => "Permitir valor vacío", "TYPE" => "Boolean", "KEEP_KEY_ON_EMPTY" => false],
                "REQUIRED" => ["TYPE" => "Boolean", "DEFAULT" => false, "LABEL" => "Requerido", "KEEP_KEY_ON_EMPTY" => false]
            ]
        ],$parentType,$value,$validationMode);

    }
}
