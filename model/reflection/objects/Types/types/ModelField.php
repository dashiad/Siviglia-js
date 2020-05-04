<?php namespace model\reflection\Types\types;

class ModelField extends \lib\model\types\Container
{
    function __construct(){
        parent::__construct( [
            "TYPE"=>"Container",
            "FIELDS"=>[
                "MODEL"=>[
                    "LABEL"=>"Modelo",
                    "TYPE"=>"String",
                    "SOURCE"=>[
                        "TYPE"=>"DataSource",
                        "MODEL"=> "/model/reflection/Model",
                        "DATASOURCE"=> "ModelList",
                        "LABEL"=> "smallName",
                        "VALUE"=> "smallName"

                    ]],
                "FIELD"=>[
                    "LABEL"=>"Campo",
                    "TYPE"=> "String",
                    "SOURCE"=> [
                        "TYPE"=> "DataSource",
                        "MODEL"=> "/model/reflection/Model",
                        "DATASOURCE"=> "FieldList",
                        "PARAMS"=> [
                            "model"=> "[%#../MODEL%]",
                        ],
                        "LABEL"=> "NAME",
                        "VALUE"=> "NAME"
                    ]
                ]
            ]
        ]);

    }
}
