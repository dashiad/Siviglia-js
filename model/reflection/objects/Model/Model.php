<?php


namespace model\reflection;


use lib\model\BaseTypedObject;

class Model extends \lib\model\BaseTypedModel
{
    protected $__targetModelName;
    protected $__normalizedModelName;
    protected $__instance;
    function __construct($targetModel)
    {
        $this->__targetModelName=str_replace('/','\\',$targetModel);
        $this->__normalizedModelName=str_replace('\\','/',$targetModel);
        parent::__construct();
    }

    function loadFromFields()
    {
        $instance=\lib\model\ModelService::getModel($this->__targetModelName);
        $def=$instance->getDefinition();
        $def["MODEL"]=$this->__normalizedModelName;


        $this->definition=$def;

    }
    function getModelDescriptor()
    {
        return $this->__objName;
    }
    function getClassName()
    {
        return $this->__normalizedModelName;
    }
    function getReflectedModel()
    {
        if($this->__instance==null)
        {
            $s=\Registry::getService("model");
            $this->__instance=$s->getModel($this->__targetModelName);
        }
        return $this->__instance;
    }

}