<?php
/**
 * Created by PhpStorm.
 * User: JoseMaria
 * Date: 15/06/14
 * Time: 12:23
 */

namespace lib\model\types;
include_once(LIBPATH."/model/types/BaseType.php");
class DataReferenceException extends BaseTypeException {
    const ERR_CANT_FIND_REFERENCE=100;

    const TXT_CANT_FIND_REFERENCE="No se encuentra la referencia a %model%::%field% ";
}
class ModelField extends Container{

    var $refModel;
    var $refField;
    var $resolvedModel;
    var $resolvedField;
    var $contentType;

    function __construct($name,$definition,$parentType=null, $value=null,$validationMode=null)
    {
        $definition=[];
        $definition["TYPE"]="ModelField";
        $definition["FIELDS"]=[
            "TYPE"=>["TYPE"=>"String","FIXED"=>"ModelField"],
            "MODEL"=>["TYPE"=>"String",
                "SOURCE"=>[
                    "TYPE"=>"DataSource",
                    "MODEL"=>'\model\reflection\Model',
                    "DATASOURCE"=>'ModelList',
                    "VALUE"=>"fullName"

                    ]
                ],
            "FIELD"=>[
                "TYPE"=>"String",
                "SOURCE"=>[
                    "TYPE"=>"DataSource",
                    "MODEL"=>'\model\reflection\Model',
                    "DATASOURCE"=>'FieldList',
                    "PARAMS"=>[
                        "model"=>"[%MODEL%]"
                    ],
                    "VALUE"=>"NAME"
                ]
            ]
        ];
        parent::__construct($name,$definition,$parentType, $value,$validationMode);
    }
}
