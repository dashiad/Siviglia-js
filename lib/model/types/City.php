<?php namespace lib\model\types;
class City extends _String {
    function __construct($definition,$value=null)
    {
		$definition['MINLENGTH']=2;
		$definition['MAXLENGTH']=128;
        String::__construct($definition,$value);
    }
}
