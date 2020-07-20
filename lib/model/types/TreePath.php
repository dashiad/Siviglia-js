<?php
namespace lib\model\types;
class TreePath extends _String
{
   function __construct($name,$def,$parentType=null, $value=null,$validationMode=null)
   {
            parent::__construct($name,array("TYPE"=>"TreePath","MAXLENGTH"=>255),$parentType,$value,$validationMode);
   }
}
