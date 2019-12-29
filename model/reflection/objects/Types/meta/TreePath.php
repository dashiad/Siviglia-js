<?php
namespace model\reflection\Types\meta;
class TreePath extends _String
{
   function __construct($def,$neutralValue=null)
   {
            parent::__construct(array("MAXLENGTH"=>255),$neutralValue);
   }
}
