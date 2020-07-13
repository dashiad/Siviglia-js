<?php
/**
 * Class Percentage
 * @package lib\model\types
 *  (c) Smartclip
 */


namespace lib\model\types;


class Percentage extends Decimal
{
    function __construct($name,$def,$parentType=null, $value=null,$validationMode=null)
    {
        $def['NINTEGERS']=1;
        $def['NDECIMALS']=10;
        // Deberia aniadirse currency en la definicion.
        Decimal::__construct($name,$def,$parentType, $value,$validationMode);
    }
    function _validate($value)
    {
        if($value>1 || $value<0)
            return false;
        return true;
    }

}
