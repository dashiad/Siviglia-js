<?php namespace model\reflection\Types\types;


class Relationship extends \lib\model\types\Container
{
    function __construct($name,$parentType=null, $value=null,$validationMode=null){
parent::__construct($name, [
            "TYPE"=>"Container",
            "FIELDS"=>[
                "TYPE"=>["LABEL"=>"Type","TYPE"=>"String","FIXED"=>"Relationship"],
                "MODEL"=>[
                    "LABEL"=>"Model",
                    "TYPE"=>"String",
                    "REQUIRED"=>true,
                    "SOURCE"=>[
                        "TYPE"=>"DataSource",
                        "MODEL"=>'\model\reflection\Model',
                        "DATASOURCE"=>'ModelList',
                        "LABEL_EXPRESSION"=>"[%/package%] > [%/smallName%]",
                        "VALUE"=>"fullName"
                    ]],
                "FIELDS"=>[
                    "LABEL"=>"Campo local",
                    "TYPE"=>"Dictionary",
                    "SOURCE"=>[
                        "TYPE"=>"Path",
                        // Este path va:
                        // El primer ".." sale del campo "FIELDS" actual (un par de lineas mas arriba)
                        // El segundo ".." sale del campo "FIELDS" de este tipo (al principio de la definicion del tipo)
                        // El tercer ".." sale de este tipo
                        // El cuarto llega al diccionario padre.
                        "PATH"=>"#../../../../[[KEYS]]",
                        "LABEL"=>"LABEL",
                        "VALUE"=>"LABEL"

                    ],
                    "VALUETYPE"=>[
                        "LABEL"=>"Campo remoto",
                        "TYPE"=>"String",
                        "SOURCE"=>[
                            "TYPE"=>"DataSource",
                            "MODEL"=>'\model\reflection\Model',
                            "DATASOURCE"=>'FieldList',
                            "PARAMS"=>[
                                "model"=>"[%#../../MODEL%]"
                            ],
                            "LABEL"=>"NAME",
                            "VALUE"=>"NAME"
                        ]
                    ],
                    "REQUIRED"=>true

                    ],
                "MULTIPLICITY"=>["LABEL"=>"Multiplicidad","TYPE"=>"Enum","VALUES"=>["1:1","1:N","M:N"]],
                "HELP"=>["LABEL"=>"Ayuda","TYPE"=>"Text","KEEP_KEY_ON_EMPTY"=>false],
                "KEEP_KEY_ON_EMPTY"=>["LABEL"=>"Permitir valor vacío","TYPE"=>"Boolean","KEEP_KEY_ON_EMPTY"=>false],
                "REQUIRED"=>["TYPE"=>"Boolean","DEFAULT"=>false,"LABEL"=>"Requerido","KEEP_KEY_ON_EMPTY"=>false],
                "DEFAULT"=>["TYPE"=>"String","LABEL"=>"Valor por defecto","KEEP_KEY_ON_EMPTY"=>false],
                "SOURCE"=>\model\reflection\Types::getSourceMeta()
            ]
        ],$parentType,$value,$validationMode);

    }
}
