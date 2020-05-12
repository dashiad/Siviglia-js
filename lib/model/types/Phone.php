<?php namespace lib\model\types;
class Phone extends _String {
    function __construct($name,$def,$parentType=null, $value=null,$validationMode=null)
    {
		$def['MINLENGTH']=7;
		$def['MAXLENGTH']=12;
		$def['REGEXP']='/[0-9\\-]{7,12}/';

        parent::__construct($name,$def,$parentType, $value,$validationMode);
    }

    function getMetaClassName()
    {
        include_once(PROJECTPATH."/model/reflection/objects/Types/Phone.php");
        return '\model\reflection\Types\meta\Phone';
    }


}
