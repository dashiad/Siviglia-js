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
            $this->definition["LABEL"]="[%LABEL%]";
            $this->definition["VALUE"]="VALUE";
            }
    }
    function getData()
    {
        if(isset($this->definition["PATH"])) {

            $parsedPath=\lib\php\ParametrizableString::getParametrizedString($this->definition["PATH"],$this->parent);
            if($parsedPath[0]=="/")
                $parsedPath=substr($parsedPath,1);
            $parts=explode("/",$parsedPath);
            $pointer=& $this->definition["DATA"];
            for($k=0;$k<count($parts);$k++)
                $pointer=& $pointer[$parsedPath[$k]];
            return $pointer;
        }

        return $this->source;
    }
}
