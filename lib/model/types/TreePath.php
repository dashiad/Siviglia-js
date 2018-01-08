<?php
namespace lib\model\types;
class TreePath extends _String
{
   function __construct($def,$neutralValue=null)
   {       
            BaseType::__construct(array("MAXLENGTH"=>255),$neutralValue);
   }
}
class TreePathMYSQLSerializer extends _StringMYSQLSerializer
{
}
?>
