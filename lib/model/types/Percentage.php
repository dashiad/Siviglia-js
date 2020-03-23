<?php
/**
 * Class Percentage
 * @package lib\model\types
 *  (c) Smartclip
 */


namespace lib\model\types;


class Percentage extends Decimal
{
    function __construct($definition,$value=null)
    {
        $definition['NINTEGERS']=1;
        $definition['NDECIMALS']=10;
        // Deberia aniadirse currency en la definicion.
        Decimal::__construct($definition,$value);
    }
    function _validate($value)
    {
        if($value>1 || $value<0)
            return false;
        return true;
    }
    function getMetaClassName()
    {
        include_once(PROJECTPATH."/model/reflection/objects/Types/Percentage.php");
        return '\model\reflection\Types\meta\Percentage';
    }

}
