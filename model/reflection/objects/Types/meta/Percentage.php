<?php
/**
 * Class Percentage
 * @package model\reflection\Types\meta
 *  (c) Smartclip
 */


namespace model\reflection\Types\meta;


class Percentage extends Decimal
{
    function __construct($definition,$value=null)
    {
        $definition['NINTEGERS']=1;
        $definition['NDECIMALS']=10;
        // Deberia aniadirse currency en la definicion.
        Decimal::__construct($definition,$value);
    }
    function validate($value)
    {
        if($value>1 || $value<0)
            return false;
        return true;
    }
}
