<?php namespace lib\model\types;
  class Name extends _String
  {
      function __construct($def,$value=false)
      {
          parent::__construct(array("TYPE"=>"Name","MAXLENGTH"=>100),$value);
      }
      static function normalize($cad)
      {
          $cad=parent::normalize($cad);
          return $cad;

      }

      function getMetaClassName()
      {
          include_once(PROJECTPATH."/model/reflection/objects/Types/meta/Name.php");
          return '\model\reflection\Types\meta\Name';
      }

  }
?>
