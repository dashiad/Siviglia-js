<?php
/**
 * Class ModelProvider
 * @package lib\model\types\libs
 *  (c) Smartclip
 */


namespace lib\model;


class Package
{
    var $basePath;
    var $baseNamespace;

    function __construct($baseNamespace, $basePath)
    {
        $this->basePath = PROJECTPATH.$basePath;
        $this->baseNamespace = $baseNamespace;
    }

    function getBasePath()
    {
        return $this->basePath;
    }

    function getBaseNamespace()
    {
        return $this->baseNamespace;
    }

    function getModelDescriptor($objectName)
    {
        return new \lib\model\ModelDescriptor($objectName,null,$this);
    }

    function includeFile($className)
    {
        return $this->includeModel($className);
    }

    function includeModel($modelName)
    {
        $descriptor = $this->getModelDescriptor($modelName, $this);
        $descriptor->includeModel();
    }


}
