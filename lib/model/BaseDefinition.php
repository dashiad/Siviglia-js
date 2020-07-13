<?php
/**
 * Created by PhpStorm.
 * User: Usuario
 * Date: 30/01/2018
 * Time: 14:35
 */

namespace lib\model;

class BaseDefinitionException extends \lib\model\BaseModelException
{
    const ERR_REQUIRED_PARAM=1;
    const TXT_REQUIRED_PARAM="El parametro [%name%] es requerido";
}
abstract class BaseDefinition extends BaseTypedObject
{
    function __construct($definition)
    {
        parent::__construct($definition);
    }
    function loadFields($fullData,$serializer)
    {
        $def=$this->getDefinition();
        foreach ($def["FIELDS"] as $getKey => $getDef) {
            if (!isset($fullData[$getKey])) {
                if (isset($getDef["REQUIRED"])) {
                    throw new BaseDefinitionException(BaseDefinitionException::ERR_REQUIRED_PARAM, array("name" => $getKey));
                } else
                    unset($def["FIELDS"][$getKey]);
            } else {
                if (is_object($fullData[$getKey])) {
                    $this->{"*".$getKey}= $fullData[$getKey]->getValue();
                    continue;
                }
                if($serializer->getSerializerType()=="PHP")
                    $this->{$getKey}=$fullData[$getKey];
                else
                    $serializer->unserializeType($getKey,$this->{"*" . $getKey}, $fullData,$this);
            }
        }
    }
    abstract function getPermissionsTarget();

    function checkPermissions($user)
    {
        $def=$this->getDefinition();
        if(!isset($def["PERMISSIONS"]))
        {
            // TODO : FIRE A WARNING
            return true;
        }
        $permissions=\Registry::getService("permissions");
        return $permissions->canAccess($def["PERMISSIONS"]);
    }


}
