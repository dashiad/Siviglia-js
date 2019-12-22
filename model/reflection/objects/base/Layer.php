<?php
namespace model\reflection\base;
class Layer extends \model\reflection\base\ClassFileGenerator
{
    var $layer;
    var $configInstance;
    var $serializer;
    function __construct($layer)
    {
        $this->layer=$layer;
        if(is_file(PROJECTPATH."/".$layer."/config/Config.php"))
        {
              $className='\\'.$layer.'\config\Config';
              $this->configInstance=new $className();
        }
    }

    function getSerializer()
    {
       if(!$this->serializer)
       {

       }
       return $this->serializer;
    }
    function rebuildStorage()
    {

        if(!$this->configInstance->definition["DONT_REBUILD_DATASPACE"])
        {


            $ser=$this->getSerializer();

            if($this->serializer->existsDataSpace($dS))
                  $this->serializer->destroyDataSpace($dS);

            $this->serializer->createDataSpace($dS);

            $this->serializer->useDataSpace($dS["NAME"]);
            return true;
         }
        return false;
    }
    function shouldRebuildStorage()
    {
        return !$this->configInstance->definition["DONT_REBUILD_DATASPACE"];
    }
    function getObjects()
    {
        $objects=\model\reflection\ReflectorFactory::getObjectsByLayer($this->layer);
        return $objects;

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
