<?php


namespace model\reflection\Model\types;


class TypeReference extends \lib\model\types\TypeSwitcher
{
    function __construct($name,$parentType=null, $value=null,$validationMode=null)
    {
        parent::__construct($name,[
           "LABEL"=>"Tipo",
           "TYPE"=>"TypeSwitcher",
           "ON"=>[
               // No tiene field, ya que si se le asigna una string, esa string es el valor del campo.
               ["IS"=>"String",
               "THEN"=>"NAMED_TYPE"],
               ["IS"=>"Object",
                   "THEN"=>"INLINE_TYPE"]
           ],
            "ALLOWED_TYPES"=>[
                "NAMED_TYPE"=>["LABEL"=>"Nombre del tipo","TYPE"=>"String","SOURCE"=>[
                    "TYPE"=>"DataSource",
                    "MODEL"=>"/model/reflection/Types",
                    "DATASOURCE"=>"TypeList",
                    "LABEL"=>"smallName",
                    "VALUE"=>"fullName"
                ]],
                "INLINE_TYPE"=>[
                    "LABEL"=>"Definicion de tipo",
                    "TYPE"=>"/model/reflection/Types/types/BaseType"
                ]
            ]
        ],$parentType, $value,$validationMode);
    }
}