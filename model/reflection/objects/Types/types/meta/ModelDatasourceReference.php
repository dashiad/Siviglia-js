<?php
/**
 * Class ModelDatasourceReference
 * @package model\reflection\Types\types\meta
 *  (c) Smartclip
 */

namespace model\reflection\Types\types\meta;
include_once(__DIR__ . "/BaseType.php");



class ModelDatasourceReference extends  \model\reflection\Meta
{
    function getMeta()
    {
        return [
            "TYPE"=>"Container",
            "FIELDS"=>[
                "MODEL"=>[
                    "LABEL"=>"Modelo",
                    "TYPE"=>"String",
                    "SOURCE"=>[
                        "TYPE"=>"DataSource",
                        "MODEL"=> "/model/reflection/Model",
                        "DATASOURCE"=> "ModelList",
                        "LABEL"=> "[%smallName%]",
                        "VALUE"=> "smallName"

                    ]],
                "DATASOURCE"=>[
                    "LABEL"=>"Datasource",
                    "TYPE"=> "String",
                    "SOURCE"=> [
                        "TYPE"=> "DataSource",
                        "MODEL"=> "/model/reflection/Model",
                        "DATASOURCE"=> "DatasourceList",
                        "PARAMS"=> [
                            "model"=> "[%#../modelSelector%]",
                        ],
                        "LABEL"=> "[%NAME%]",
                        "VALUE"=> "NAME"
                    ]
                ]
            ]
        ];
    }
}
