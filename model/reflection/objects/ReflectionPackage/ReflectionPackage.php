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

    static $objectTree=[];
    static $modelList=[];
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
        if(isset(ReflectionPackage::$objectTree[$this->name]))
            return ReflectionPackage::$objectTree[$this->name];
        $rawModels=$this->pkg->getModels();
        $plainModels=$this->generateReflectionModels($rawModels);
        ReflectionPackage::$objectTree[$this->name]=$rawModels;
        ReflectionPackage::$modelList[$this->name]=$plainModels;
        return $plainModels;

    }
    
    function generateReflectionModels(& $modelList)
    {
        $result=[];
        if($modelList==null)
            $modelList=[];
        for($k=0;$k<count($modelList);$k++)
        {
            $instance=new \model\reflection\Model($modelList[$k]["class"]);
            $modelList[$k]["instance"]=$instance;
            ReflectionPackage::$objectTree[$this->name][$modelList[$k]["class"]]=$instance;
            $result[]=$modelList[$k];
            if(isset($modelList[$k]["subobjects"]))
                $result=array_merge($result,$this->generateReflectionModels($modelList[$k]["subobjects"]));
        }
        return $result;
    }
    function iterateOnModels($cb)
    {
        $allModels=$this->getModels();
        // La key es el nombre de la clase, el valor es una instancia de model de reflection.
        if($allModels!==null) {
            foreach ($allModels as $k => $v) {
                call_user_func($cb, $v["instance"], $v["class"]);
            }
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
