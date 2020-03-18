<?php
/**
 * Created by PhpStorm.
 * User: Usuario
 * Date: 28/02/2018
 * Time: 14:13
 */

namespace model\reflection\base;


class AutoUIDefinition
{
    static $definition=array();
    var $subDefs=array();

    function getDefinition($params=null)
    {
        $this->subDefs=array();
        $base=static::$definition;
        if(isset($base["IMPORT"]))
        {
            for($k=0;$k<count($base["IMPORT"]);$k++)
                $this->loadSubDefinition(array(0,$base["IMPORT"][$k]));
        }
        $encoded=json_encode($base);
        $match=preg_replace_callback(
            "/\"@@([^@]+)@@\"/",
            array($this,"loadSubDefinition"),
            $encoded
        );
        $replaced=json_decode($match,true);
        foreach($this->subDefs as $k=>$v)
            $replaced["DEFINITION"][$k]=$v;
        return $replaced;
    }
    function loadSubDefinition($path)
    {
        $parts=explode("|",$path[1]);
        $instance=AutoUIDefinition::getInstance($parts[0],$parts[1]);
        $param=null;
        if(isset($parts[2]))
            $param=$parts[2];
        $subDef=$instance->getDefinition($param);
        $newDefs=$subDef["DEFINITION"];
        foreach($newDefs as $key=>$value)
        {
            if($key!="ROOT")
                $this->subDefs[$key]=$value;
        }
        if(isset($subDef["DEFINITION"]["ROOT"]))
            return json_encode($subDef["DEFINITION"]["ROOT"]);
        return "";
    }
    static function getInstance($object,$fileName)
    {
        $mName=\lib\model\ModelService::getModelDescriptor('\model\reflection\\'.$object);
        include_once($mName->getDestinationFile("/autoui/".$fileName.".php"));
        $className='\model\reflection\\'.$object.'\autoui\\'.str_replace('/','\\',$fileName);
        return new $className();
    }
}
