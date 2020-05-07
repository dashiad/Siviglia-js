<?php
namespace lib\model\types;
class TreePath extends _String
{
   function __construct($def,$value=null)
   {
            parent::__construct(array("MAXLENGTH"=>255),$value);
   }
   function getMetaClassName()
   {
        include_once(PROJECTPATH."/model/reflection/objects/Types/TreePath.php");
        return '\model\reflection\Types\meta\TreePath';
   }
}
