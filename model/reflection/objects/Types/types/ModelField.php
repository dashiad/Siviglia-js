<?php namespace model\reflection\Types\types;

include_once(__DIR__."/../BaseReflectedType.php");
class ModelField extends \model\reflection\types\BaseReflectedType
{
    function __construct($name,$parentType=null, $value=null,$validationMode=null){
        parent::__construct($name,"ModelField",[
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
            ]],$parentType,$value,$validationMode);

    }
}
