<?php
/**
 * Class ModelProvider
 * @package lib\model\types\libs
 *  (c) Smartclip
 */


namespace lib\model;


abstract class Package
{
    var $basePath;
    var $baseNamespace;
    function __construct($baseNamespace,$basePath)
    {
        $this->basePath=$basePath;
        $this->baseNamespace=$baseNamespace;
    }
    function getBasePath()
    {
        return $this->basePath;
    }
    function getBaseNamespace()
    {
        return $this->baseNamespace;
    }
    abstract function getModelDescriptor($objectName);

    function includeFile($className)
    {
        return $this->includeModel($className);
    }
    function includeModel($modelName)
    {
        $descriptor=$this->getModelDescriptor($modelName,$this);
        $descriptor->includeModel();
    }
}
