<?php namespace model\reflection\Types\types;
include_once(__DIR__."/../BaseReflectionType.php");
include_once(PROJECTPATH."/model/reflection/objects/Meta/Meta.php");
include_once(__DIR__."/ModelDatasourceReference.php");
class BaseType extends \lib\model\types\TypeSwitcher
{
    var $typeName;
    var $curVal=null;
    var $definition=null;
    var $typeInstance=null;
    static $typeCache=null;
    function __construct($definition=null)
    {
        parent::__construct($this->getMeta());
    }
    function getDefinition()
    {
        if($this->typeInstance)
            return $this->typeInstance->getValue();
        return null;
    }
    function setTypeName($name)
    {
        $this->typeName=$name;
    }
    static function geCommonMeta()
    {
        return [
            "FIXED"=>["TYPE"=>"String"],
            "DEFAULT"=>["TYPE"=>"String"]
        ];
    }
    static function getSourceMeta()
    {
        $datasourceReference=new ModelDatasourceReference();
        $dsMeta=$datasourceReference->getMeta();
        $dsMeta["PARAMS"]=[
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
                        "FIELDS"=>$dsMeta
                    ],
                    "Path"=>[
                        "TYPE"=>"String",
                        "REQUIRED"=>true
                    ]
                ]

        ];
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
                "REQUIRED"=>["TYPE"=>"Boolean","DEFAULT"=>false,"LABEL"=>"Requerido","KEEP_KEY_ON_EMPTY"=>false],
                "HELP"=>["LABEL"=>"Ayuda","TYPE"=>"Text","KEEP_KEY_ON_EMPTY"=>false],
                "KEEP_KEY_ON_EMPTY"=>["LABEL"=>"Permitir valor vacío","TYPE"=>"Boolean","KEEP_KEY_ON_EMPTY"=>false],
                "FIXED"=>["TYPE"=>"String"],
                "DEFAULT"=>["TYPE"=>"String"]
            ]
        ];
    }
    function getDerivedTypeMeta($typeName)
    {
        $baseMeta=$this->getMeta();
        $baseMeta["FIELDS"]["TYPE"]=["LABEL"=>"Tipo","TYPE"=>"String","FIXED"=>"$typeName"];
    }

}
