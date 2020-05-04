<?php
/**
 * Class ModelDatasourceReference
 * @package model\reflection\Types\types\meta
 *  (c) Smartclip
 */

namespace model\reflection\Types\types;

include_once(__DIR__ . "/BaseType.php");



class ModelDatasourceReference extends  \lib\model\types\Container
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
                "DATASOURCE"=>[
                    "LABEL"=>"Datasource",
                    "TYPE"=> "String",
                    "SOURCE"=> [
                        "TYPE"=> "DataSource",
                        "MODEL"=> "/model/reflection/DataSource",
                        "DATASOURCE"=> "DatasourceList",
                        "PARAMS"=> [
                            "model"=> "[%#../MODEL%]",
                        ],
                        "LABEL"=> "name",
                        "VALUE"=> "name"
                    ]
                ],
                "PARAMS"=>[
                    "LABEL"=>"Parametros extra",
                    "TYPE"=>"Dictionary",
                    "VALUETYPE"=>[
                        "LABEL"=>"Valor",
                        "TYPE"=>"String"
                    ]
                ]
            ]
        ]);

    }
}
