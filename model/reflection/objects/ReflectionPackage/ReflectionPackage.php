<?php
/**
 * Class Package
 * @package model\reflection\Package
 *  (c) Smartclip
 */


namespace model\reflection;


class ReflectionPackage
{
    function __construct($packageName)
    {
        $this->name=$packageName;
        $service=\Registry::getService("model");
        $this->pkg=$service->getPackageByName($packageName);
    }
    function getModels($path=null,$prefix=null,$childKey="subobjects")
    {
        if($path==null)
            $path=$this->pkg->getBasePath();
        $path=$path."/objects";
        if(!is_dir($path))
            return null;
        $dir = new \DirectoryIterator($path);
        $objects=array();
        if($prefix!=null)
            $prefix.='\\';
        else
            $prefix=$this->pkg->getBaseNamespace();
        foreach ($dir as $fileinfo) {
            if (!$fileinfo->isDot() && $fileinfo->isDir()) {
                $name=$fileinfo->getFilename();
                $current=array(
                    "name"=>$name,
                    "layer"=>$this->name,
                    "path"=>$fileinfo->getRealPath(),
                    "class"=>$prefix.$name
                );
                $subobjects=$this->getModels($current["path"],$current["class"],$childKey);
                if($subobjects)
                    $current[$childKey]=$subobjects;
                $objects[]=$current;
            }
        }
        return $objects;
    }
}
