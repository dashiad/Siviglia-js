<?php
/**
 * Class BaseTypedObject
 * @package model\reflection\Model\types
 *  (c) Smartclip
 */

namespace model\reflection\Model\types;

class BaseTypedObject extends \lib\model\types\Container
{
    function __construct()
    {
        parent::__construct([
            "TYPE"=>"Container",
            "LABEL"=>"BaseTypedObject",
            "FIELDS"=>[
                "FIELDS"=>[
                    "LABEL"=>"Campos",
                    "TYPE"=>"Dictionary",
                    "VALUETYPE"=>"/model/reflection/Model/types/TypeReference"
                ]
            ]
        ]);
    }
}

