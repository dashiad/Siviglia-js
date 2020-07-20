<?php
/**
 * Class BaseTypedObject
 * @package model\reflection\Model\types
 *  (c) Smartclip
 */

namespace model\reflection\Model\types;

class BaseTypedObject extends \lib\model\types\Container
{
    function __construct($name,$parentType=null, $value=null,$validationMode=null)
    {
        parent::__construct($name,[
            "TYPE"=>"Container",
            "LABEL"=>"BaseTypedObject",
            "FIELDS"=>[
                "FIELDS"=>[
                    "LABEL"=>"Campos",
                    "TYPE"=>"Dictionary",
                    "VALUETYPE"=>"/model/reflection/Model/types/TypeReference"
                ],
                "STATES" => [
                    "LABEL" => "Estados",
                    "TYPE" => "/model/reflection/Model/types/StateSpec",
                    "KEEP_ON_EMPTY" => false
                ]
            ],

        ],$parentType,$value,$validationMode);
    }
}

