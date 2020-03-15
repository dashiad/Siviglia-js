<?php namespace model\reflection\Types\types\meta;
include_once(PROJECTPATH."/model/reflection/objects/Meta/Meta.php");

class BaseType extends \model\reflection\Meta
{
    static function geCommonMeta()
    {
        return [
            "FIXED"=>["TYPE"=>"String"],
            "DEFAULT"=>["TYPE"=>"String"]
        ];
    }
    static function getSourceMeta()
    {
        $datasourceReference=\model\reflection\Meta::importDefinition(
            "types/meta/ModelDatasourceReference.php",
            'types\meta\ModelDatasourceReference'
        );
        $datasourceReference["PARAMS"]=[
            "TYPE"=>"DICTIONARY",
            "VALUETYPE"=>[
                "TYPE"=>"String"
            ]
        ];

        return [

                "TYPE"=>"TypeSwitcher",
                "TYPE_FIELD"=>"TYPE",
                "ALLOWED_TYPES"=>[
                    "Array"=>[
                        "TYPE"=>"Container",
                        "FIELDS"=>[
                            "TYPE"=>["TYPE"=>"String","FIXED"=>"Array"],
                            "DATA"=>["TYPE"=>"Array",
                                     "ELEMENTS"=>[
                                         "TYPE"=>"CONTAINER",
                                         "REQUIRED"=>true,
                                         "FIELDS"=>[
                                             "Id"=>["TYPE"=>"Integer"],
                                             "Label"=>["TYPE"=>"String"],
                                             "Extra"=>["TYPE"=>"String"]
                                         ]
                                     ],
                                "PATH"=>["TYPE"=>"String"]
                            ]
                        ]
                    ],
                    "DataSource"=>[
                        "TYPE"=>"Container",
                        "FIELDS"=>$datasourceReference
                    ],
                    "Path"=>[
                        "TYPE"=>"String",
                        "REQUIRED"=>true
                    ]
                ]

        ];
    }
    static function getAllTypeClasses()
    {
        $src=glob(__DIR__."/*.php");
        $result=[];
        for($k=0;$k<count($src);$k++)
        {
            $cur=basename($src[$k]);
            if($cur!=="BaseType.php")
            {
                $p=explode(".",$cur);
                $result[]="/model/reflection/Types/types/".$p[0];
            }
        }
        return $result;
    }
    function getMeta()
    {
        return [
            "TYPE"=>"Container",
            "FIELDS"=>[
                "TYPE"=>[
                    "LABEL"=>"Type",
                    "REQUIRED"=>true,
                    "TYPE"=>"TypeSwitcher",
                    "TYPE_FIELD"=>"TYPE",
                    "ALLOWED_TYPES"=>BaseType::getAllTypeClasses()
                ],
                "LABEL"=>["TYPE"=>"String"],
                "REQUIRED"=>["TYPE"=>"Boolean","DEFAULT"=>false,"LABEL"=>"Requerido","SET_ON_EMPTY"=>false],
                "HELP"=>["LABEL"=>"Ayuda","TYPE"=>"Text","SET_ON_EMPTY"=>false],
                "SET_ON_EMPTY"=>["LABEL"=>"Permitir valor vacío","TYPE"=>"Boolean","SET_ON_EMPTY"=>false],
                "FIXED"=>["TYPE"=>"String"],
                "DEFAULT"=>["TYPE"=>"String"]
            ]
        ];
    }
}
