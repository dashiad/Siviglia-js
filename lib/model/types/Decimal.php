<?php namespace lib\model\types;
class Decimal extends BaseType
{
    function setValue($val)
    {
        if($val===null || !isset($val))
        {
            $this->valueSet=false;
            $this->value=null;
        }
        else
        {
            $this->valueSet=true;
            $this->value=$val;
        }
    }
}
