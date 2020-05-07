<?php namespace lib\model\types;
class Phone extends _String {
    function __construct(& $definition,$value=null)
    {
		$definition['MINLENGTH']=7;
		$definition['MAXLENGTH']=12;
		$definition['REGEXP']='/[0-9\\-]{7,12}/';

        parent::__construct($definition,$value);
    }

    function getMetaClassName()
    {
        include_once(PROJECTPATH."/model/reflection/objects/Types/Phone.php");
        return '\model\reflection\Types\meta\Phone';
    }


}
