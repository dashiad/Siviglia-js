<?php


namespace lib\model\types\sources;
/*
 *   Definition:
 *   "SOURCE":[
 *      "TYPE":"Array",
 *      "DATA":[
 *              [key1:value1,key2:value2,key3:value3]...]
 *      "LABEL":<field>,
 *      "VALUE":<field>
 *   ]
 *
 */

use lib\model\PathResolver;

class ArraySource extends BaseSource
{
    var $source;
    function __construct($parent,$definition,$useValidatingData=false)
    {
        parent::__construct($parent,$definition);
        if(isset($this->__definition["DATA"]))
            $this->source=$this->__definition["DATA"];
        if(isset($this->__definition["VALUES"])) {
            $idx=-1;
            $this->source=array_map(function($item) use (&$idx) {$idx++;return ["VALUE"=>$idx,"LABEL"=>$item];},$this->__definition["VALUES"]);
            if(!isset($this->__definition["LABEL"]))
                $this->__definition["LABEL"]="LABEL";
            if(!isset($this->__definition["VALUE"]))
                $this->__definition["VALUE"]="VALUE";
            }
    }
    function getData()
    {
        if(isset($this->__definition["PATH"])) {
            $ctxStack=new \lib\model\ContextStack();
            $ctx=new \lib\model\BaseObjectContext($this->__definition["DATA"],"/",$ctxStack);
            $ctx=new \lib\model\BaseObjectContext($this->parent,"#",$ctxStack);
            $path=new \lib\model\PathResolver($ctxStack,$this->__definition["PATH"]);
            $data=$path->getPath();
        }
        else
            $data=$this->source;
        if(isset($this->__definition["PREPEND"]))
            $data=array_merge($this->__definition["PREPEND"],$data);
        if(isset($this->__definition["APPEND"]))
            $data=array_merge($data,$this->__definition["APPEND"]);
        return $data;

    }
}
