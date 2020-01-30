<?php


namespace lib\model\types\sources;


abstract class BaseSource
{
    var $parent;
    var $definition;
    var $useValidatingData;

    function __construct($parent,$definition,$useValidatingData=false)
    {
        $this->parent=$parent;
        $this->definition=$definition;
        $this->useValidatingData=$useValidatingData;
    }

    abstract function getData();
    function contains($value)
    {
        if($value===null)
           return true;
        $d=$this->getData();
        $f=$this->getValueField();
        for($k=0;$k<count($d);$k++)
        {
            if($d[$k][$f]==$value)
                return true;

        }
        return false;
    }
    function getValue($row)
    {
        return $row[$this->getValueField()];
    }
    function getLabel($row)
    {
        if(isset($this->definition["LABEL"]))
            return \lib\php\ParametrizableString::getParametrizedString($this->definition["LABEL"],$row);
        else
            return $row["LABEL"];
    }
    function getLabelField()
    {

        return isset($this->definition["LABEL"])?$this->definition["LABEL"]:"LABEL";
    }
    function getValueField()
    {
        return isset($this->definition["VALUE"])?$this->definition["VALUE"]:"VALUE";
    }
    function getDefinition()
    {
        return $this->definition;
    }
}
