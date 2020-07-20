<?php namespace model\reflection\Types\types;


include_once(__DIR__."/../BaseReflectedType.php");
class Relationship extends \model\reflection\types\BaseReflectedType
{
    function __construct($name,$parentType=null, $value=null,$validationMode=null){
parent::__construct($name, "Relationship",[
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
                "MULTIPLICITY"=>["LABEL"=>"Multiplicidad","TYPE"=>"Enum","VALUES"=>["1:1","1:N","M:N"]]
            ],$parentType,$value,$validationMode);

    }
}
