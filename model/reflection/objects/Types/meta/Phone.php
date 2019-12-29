<?php namespace model\reflection\Types\meta;
class Phone extends _String {
    function __construct(& $definition,$value)
    {
		$definition['MINLENGTH']=7;
		$definition['MAXLENGTH']=12;
		$definition['REGEXP']='/[0-9\\-]{7,12}/';

        String::__construct($definition,$value);
    }

}
