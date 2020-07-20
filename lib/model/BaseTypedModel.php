<?php

namespace lib\model;
/*
 * Esta clase sirve para representar objeto que existen dentro de la jerarquia de /model/, pero que no son
 * serializables.
 */
class BaseTypedModel extends BaseTypedObject
{

    protected $__objName;
    protected $__def;

    function __construct($definition = null,$validationMode=null)
    {
        $this->__objName = \lib\model\ModelService::getModelDescriptor('\\'.get_class($this));
        if (!$definition)
            $this->__def=BaseModelDefinition::loadDefinition($this);
        else
            $this->__def=BaseModelDefinition::fromArray($definition);

        BaseTypedObject::__construct($this->__def->getDefinition(),$validationMode);
    }
    function __getObjectName()
    {
        return $this->__objName->className;
    }
    function __getObjectNameObj()
    {
        return $this->__objName;
    }
    function __getModelDescriptor()
    {
        return $this->__objName;
    }
    function __getFullObjectName()
    {
        return $this->__objName->getNamespaced();
    }
    function __getObjectDefinition()
    {
        return $this->__objectDef;
    }
}

