<?php namespace model\reflection\Types\meta;

namespace model\reflection\Types\meta;
class Image extends \model\reflection\Meta\BaseMetadata
{
    function getMeta()
    {
        return [
            "TYPE" => "Container",
            "FIELDS" => [
                "TYPE" => ["TYPE" => "String", "FIXED" => "Image"],
                "MINSIZE"=>["TYPE"=>"Integer","LABEL"=>"Tamaño mínimo","SET_ON_EMPTY"=>false],
                "MAXSIZE"=>["TYPE"=>"Integer","LABEL"=>"Tamaño máximo","SET_ON_EMPTY"=>false],
                "EXTENSIONS"=>["TYPE"=>"Array","LABEL"=>"Extensiones","ELEMENTS"=>["TYPE"=>"String"],"REQUIRED"=>true,"SET_ON_EMPTY"=>false],
                "TARGET_FILENAME"=>["TYPE"=>"String","LABEL"=>"Nombre de destino","REQUIRED"=>true],
                "TARGET_FILEPATH"=>["TYPE"=>"String","LABEL"=>"Path destino","REQUIRED"=>true],
                "PATH_TYPE" => ["TYPE"=>"ENUM","LABEL"=>"Tipo de path", "VALUES"=>["ABSOLUTE","RELATIVE"],"REQUIRED"=>true],
                "MINWIDTH"=>["TYPE"=>"Integer","LABEL"=>"Ancho mínimo","REQUIRED"=>false,"SET_ON_EMPTY"=>false],
                "MAXWIDTH"=>["TYPE"=>"Integer","LABEL"=>"Ancho máximo","REQUIRED"=>false,"SET_ON_EMPTY"=>false],
                "MINHEIGHT"=>["TYPE"=>"Integer","LABEL"=>"Altura mínima","REQUIRED"=>false,"SET_ON_EMPTY"=>false],
                "MAXHEIGHT"=>["TYPE"=>"Integer","LABEL"=>"Altura máxima","REQUIRED"=>false,"SET_ON_EMPTY"=>false],
                "THUMBNAIL"=>["TYPE"=>"Container","LABEL"=>"Thumbnail","REQUIRED"=>false,"SET_ON_EMPTY"=>false,
                    "FIELDS"=>[
                        "ENABLED"=>["TYPE"=>"Boolean","LABEL"=>"Crear thumbnail","DEFAULT"=>false,"SET_ON_EMPTY"=>false],
                        "WIDTH"=>["TYPE"=>"Integer","LABEL"=>"Ancho mínimo","REQUIRED"=>false,"SET_ON_EMPTY"=>false],
                        "HEIGHT"=>["TYPE"=>"Integer","LABEL"=>"Ancho mínimo","REQUIRED"=>false,"SET_ON_EMPTY"=>false],
                        "PREFIX"=>["TYPE"=>"String","LABEL"=>"Prefijo","REQUIRED"=>true],
                        "QUALITY"=>["TYPE"=>"Percentage","LABEL"=>"Calidad"],
                        "KEEPASPECT"=>["TYPE"=>"Boolean","LABEL"=>"Mantener aspecto","DEFAULT"=>true]
                        ]
                ],
                "WATERMARK"=>["TYPE"=>"Container","LABEL"=>"Watermark","REQUIRED"=>false,"SET_ON_EMPTY"=>false,
                    "FIELDS"=>[
                        "ENABLED"=>["TYPE"=>"Boolean","LABEL"=>"Añadir watermark","DEFAULT"=>false,"SET_ON_EMPTY"=>false],
                        "POSITION"=>["TYPE"=>"Enum","LABEL"=>"Posición","VALUES"=>["NW","NE","SE","SW","CENTER"]],
                        "FILE"=>["TYPE"=>"String","LABEL"=>"Fichero watermark"]
                        ]
                ],
                "DESCRIPTION"=>["TYPE"=>"Text","LABEL"=>"Descripción","REQUIRED"=>false,"SET_ON_EMPTY"=>false],
                "AUTODELETE" => ["TYPE"=>"Boolean","LABEL"=>"Borrado automático","DEFAULT"=>false],
                "HELP" => ["LABEL" => "Ayuda", "TYPE" => "Text", "SET_ON_EMPTY" => false],
                "SET_ON_EMPTY" => ["LABEL" => "Permitir valor vacío", "TYPE" => "Boolean", "SET_ON_EMPTY" => false],
                "REQUIRED" => ["TYPE" => "Boolean", "DEFAULT" => false, "LABEL" => "Requerido", "SET_ON_EMPTY" => false]
            ]
        ];
    }
}
