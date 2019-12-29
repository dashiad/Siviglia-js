<?php namespace lib\model\types;
  class Color extends _String
  {
      function __construct($def,$value=false)
      {
          parent::__construct(array("TYPE"=>"Color","MAXLENGTH"=>10),$value);
      }
      function getMetaClassName()
      {
          include_once(PROJECTPATH."/model/reflection/objects/Types/meta/Color.php");
          return '\model\reflection\Types\meta\Color';
      }
  }
?>
