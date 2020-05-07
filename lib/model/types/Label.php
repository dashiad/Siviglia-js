<?php namespace lib\model\types;
  class Label extends _String
  {
      function __construct($def,$value=null)
      {
          parent::__construct(array("TYPE"=>"Label","MAXLENGTH"=>50),$value);
      }

      function getMetaClassName()
      {
          include_once(PROJECTPATH."/model/reflection/objects/Types/Label.php");
          return '\model\reflection\Types\meta\Label';
      }

  }
?>
