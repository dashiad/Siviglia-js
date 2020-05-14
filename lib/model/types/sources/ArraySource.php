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
        if(isset($this->definition["DATA"]))
            $this->source=$this->definition["DATA"];
        if(isset($this->definition["VALUES"])) {
            $idx=-1;
            $this->source=array_map(function($item) use (&$idx) {$idx++;return ["VALUE"=>$idx,"LABEL"=>$item];},$this->definition["VALUES"]);
            if(!isset($this->definition["LABEL"]))
                $this->definition["LABEL"]="LABEL";
            if(!isset($this->definition["VALUE"]))
                $this->definition["VALUE"]="VALUE";
            }
    }
    function getData()
    {
        if(isset($this->definition["PATH"])) {
            $ctxStack=new \lib\model\ContextStack();
            $ctx=new \lib\model\BaseObjectContext($this->definition["DATA"],"/",$ctxStack);
            $ctx=new \lib\model\BaseObjectContext($this->parent,"#",$ctxStack);
            $path=new \lib\model\PathResolver($ctxStack,$this->definition["PATH"]);
            $data=$path->getPath();
        }
        else
            $data=$this->source;
        if(isset($this->definition["PREPEND"]))
            $data=array_merge($this->definition["PREPEND"],$data);
        if(isset($this->definition["APPEND"]))
            $data=array_merge($data,$this->definition["APPEND"]);
        return $data;

    }
}
