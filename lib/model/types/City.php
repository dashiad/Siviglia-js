<?php namespace lib\model\types;
class City extends _String {
    function __construct($name,$definition,$parentType=null, $value=null,$validationMode=null)
    {
		$definition['MINLENGTH']=2;
		$definition['MAXLENGTH']=128;
        parent::__construct($name,$definition,$parentType,$value,$validationMode);
    }

}
