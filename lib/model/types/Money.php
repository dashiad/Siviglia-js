<?php namespace lib\model\types;
class Money extends Decimal {
    function __construct($name,$def,$parentType=null, $value=null,$validationMode=null)
    {
		$def['NINTEGERS']=20;
		$def['NDECIMALS']=3;
        // Deberia aniadirse currency en la definicion.
        Decimal::__construct($name,$def,$parentType, $value,$validationMode);
    }
    static function getFormatted($value)
    {
        return money_format('%=*(#10.2n', $value);
    }

    function getMetaClassName()
    {
        include_once(PROJECTPATH."/model/reflection/objects/Types/Money.php");
        return '\model\reflection\Types\meta\Money';
    }

}
