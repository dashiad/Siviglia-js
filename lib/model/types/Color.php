<?php namespace lib\model\types;
  class Color extends _String
  {
      function __construct($def,$value=null)
      {
          parent::__construct(array("TYPE"=>"Color","MAXLENGTH"=>10),$value);
      }
      function getMetaClassName()
      {
          include_once(PROJECTPATH."/model/reflection/objects/Types/Color.php");
          return '\model\reflection\Types\meta\Color';
      }
  }
?>
