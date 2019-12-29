<?php
/**
 * Class Package
 * @package model\reflection\Package
 *  (c) Smartclip
 */


namespace model\reflection;


class ReflectionPackage
{
    var $name;
    var $pkg;
    var $configInstance;
    static $objectCache;
    static $objectTree;
    function __construct($packageName)
    {
        $this->name=$packageName;
        $service=\Registry::getService("model");
        $this->pkg=$service->getPackageByName($packageName);
        $this->configInstance=$this->pkg->getConfig();
    }
    function getName()
    {
        return $this->name;
    }
    function getPackage()
    {
        return $this->pkg;
    }
    function getModels()
    {
        if(isset(ReflectionPackage::$objectCache[$this->name]))
            return ReflectionPackage::$objectCache[$this->name];
        $rawModels=$this->pkg->getModels();
        $this->generateReflectionModels($rawModels);
        ReflectionPackage::$objectTree[$this->name]=$rawModels;
        return ReflectionPackage::$objectCache[$this->name];

    }

    function generateReflectionModels(& $modelList)
    {
        for($k=0;$k<count($modelList);$k++)
        {
            $instance=new \model\reflection\Model($modelList[$k]["class"]);
            $modelList[$k]["instance"]=$instance;
            ReflectionPackage::$objectCache[$this->name][$modelList[$k]["class"]]=$instance;
            if(isset($modelList["subobjects"]))
                $this->generateReflectionModels($modelList["subobjects"]);
        }
    }
    function iterateOnModels($cb)
    {
        $allModels=$this->getModels();
        // La key es el nombre de la clase, el valor es una instancia de model de reflection.
        foreach($allModels as $k=>$v)
        {
            call_user_func($cb,$v["instance"],$v["class"]);
        }
    }

    function iterateOnModelTree($cb)
    {
        $allModels=$this->getModels();
        $this->_iterateOnModelTree(ReflectionPackage::$objectTree[$this->name],$cb);
    }
    function _iterateOnModelTree($list,$cb)
    {
        for($k=0;$k<count($list);$k++)
        {
            call_user_func($cb,$list[$k]["instance"],$list[$k]["class"]);
            if(isset($list[$k]["subobjects"]))
                $this->_iterateOnModelTree($list[$k]["subobjects"],$cb);
        }
    }
    function getQuickDefinitions()
    {
        return $this->configInstance->definition["QuickDef"];
    }
    function getPermissionsDefinition()
    {
        return $this->configInstance->permissions;
    }

}
