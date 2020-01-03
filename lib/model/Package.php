<?php
/**
 * Class ModelProvider
 * @package lib\model\types\libs
 *  (c) Smartclip
 */


namespace lib\model;


class Package
{
    var $name;
    var $basePath;
    var $baseNamespace;
    var $fullPath;
    var $configInstance;

    function __construct($baseNamespace, $basePath)
    {
        $parts=explode('\\',$baseNamespace);
        $k=0;
        while($parts[$k]=="" || $parts[$k]=="model")$k++;
        $this->name=$parts[$k];
        $this->basePath = PROJECTPATH.$basePath;
        $this->baseNamespace = $baseNamespace;
        $this->fullPath=$this->basePath.DIRECTORY_SEPARATOR.str_replace('\\',DIRECTORY_SEPARATOR,$baseNamespace);
        $this->configInstance=null;
        $configFilePath=$this->fullPath."/config/Config.php";
        if(is_file($configFilePath))
        {
            include_once($configFilePath);
            $className=$baseNamespace.'\config\Config';
            $this->configInstance=new $className();
        }
    }
    function getName()
    {
        return $this->name;
    }
    function getConfig()
    {
        return $this->configInstance;
    }

    function getBasePath()
    {
        return $this->basePath;
    }
    function getFullPath()
    {
        return $this->fullPath;
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
    function getModels($path=null,$prefix=null)
    {
        if($path==null)
            $path=$this->getFullPath();
        $path=$path."/objects";
        if(!is_dir($path))
            return null;
        $dir = new \DirectoryIterator($path);
        $objects=array();
        if($prefix!=null)
            $prefix.='\\';
        else
            $prefix=$this->getBaseNamespace();
        foreach ($dir as $fileinfo) {
            if (!$fileinfo->isDot() && $fileinfo->isDir()) {
                $name=$fileinfo->getFilename();
                $current=array(
                    "name"=>$name,
                    "package"=>$this->name,
                    "path"=>$fileinfo->getRealPath(),
                    "class"=>$prefix.'\\'.$name
                );
                $subobjects=$this->getModels($current["path"],$current["class"]);
                if($subobjects)
                    $current["subobjects"]=$subobjects;
                $objects[]=$current;
            }
        }
        return $objects;
    }


}
