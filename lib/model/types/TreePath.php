<?php
namespace lib\model\types;
class TreePath extends _String
{
   function __construct($def,$neutralValue=null)
   {
            parent::__construct(array("MAXLENGTH"=>255),$neutralValue);
   }
}
