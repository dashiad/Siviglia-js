<?php
/**
 * Created by PhpStorm.
 * User: Usuario
 * Date: 06/02/2018
 * Time: 17:14
 */

namespace model\reflection;


abstract class Meta
{
    var $definition;
    static function importDefinition($fileName,$metaDataClass)
    {
        include_once(PROJECTPATH."/model/reflection/objects/Types/".$fileName);
        $metaDataClass='\model\reflection\Types\\'.$metaDataClass;
        $instance=new $metaDataClass();
        return $instance->getMeta();
    }
    function mergeDefinition($fileName,$metaDataClass)
    {
        $d=Meta::importDefinition($fileName,$metaDataClass);
        if($this->definition==null)
            $this->definition=[];
        $this->definition=array_merge($this->definition,$d);
    }

    abstract function getMeta();
}
