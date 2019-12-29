<?php
/**
 * Created by PhpStorm.
 * User: Usuario
 * Date: 06/02/2018
 * Time: 17:14
 */

namespace model\reflection\Meta;


abstract class BaseMetadata
{
    var $definition;
    function importDefinition($fileName,$metaDataClass)
    {
        include_once(PROJECTPATH."/model/reflection/objects/".$fileName);
        $metaDataClass='\model\reflection\objects\meta\\'.$metaDataClass;
        $instance=new $metaDataClass();
        return $instance->getMeta();
    }
    function mergeDefinition($fileName,$metaDataClass)
    {
        $d=$this->importDefinition($fileName,$metaDataClass);
        if($this->definition==null)
            $this->definition=[];
        $this->definition=array_merge($this->definition,$d);
    }

    abstract function getMeta();
}