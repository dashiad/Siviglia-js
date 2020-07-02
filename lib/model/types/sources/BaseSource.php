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
        $this->__definition=$definition;
        $this->useValidatingData=$useValidatingData;
    }

    abstract function getData();
    function contains($value)
    {
        if($value===null)
           return true;
        $d=$this->getData();
        if(is_a($d,'lib\model\BaseModel'))
            $d=$d->getValue();
        $f=$this->getValueField();
        for($k=0;$k<count($d);$k++)
        {
            if($d[$k][$f]===$value)
                return true;

        }
        return false;
    }
    function containsLabel($value)
    {
        if($value===null)
            return false;
        $d=$this->getData();
        $f=$this->getLabelField();
        for($k=0;$k<count($d);$k++)
        {
            if($d[$k][$f]===$value)
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
        if(isset($this->__definition["LABEL_EXPRESSION"]))
            return \lib\php\ParametrizableString::getParametrizedString($this->__definition["LABEL_EXPRESSION"],$row);
        else {
            return $row[$this->__definition["LABEL"]];
        }
    }
    function getLabelField()
    {

        return isset($this->__definition["LABEL"])?$this->__definition["LABEL"]:"LABEL";
    }
    function getLabelExpression()
    {

        return isset($this->__definition["LABEL_EXPRESSION"])?$this->__definition["LABEL_EXPRESSION"]:null;
    }
    function getValueField()
    {
        return isset($this->__definition["VALUE"])?$this->__definition["VALUE"]:"VALUE";
    }
    function getDefinition()
    {
        return $this->__definition;
    }
}
