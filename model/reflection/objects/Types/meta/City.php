<?php namespace model\reflection\Types\meta;
class City extends _String {
    function __construct($definition,$value=null)
    {
		$definition['MINLENGTH']=2;
		$definition['MAXLENGTH']=128;
        _String::__construct($definition,$value);
    }
}
