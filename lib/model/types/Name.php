<?php namespace lib\model\types;
  class Name extends _String
  {
      function __construct($name,$def,$parentType=null, $value=null,$validationMode=null)
      {
          parent::__construct($name,array("TYPE"=>"Name","MAXLENGTH"=>100),$parentType,$value,$validationMode);
      }
      static function normalize($cad)
      {
          $cad=parent::normalize($cad);
          return $cad;

      }

      function getMetaClassName()
      {
          include_once(PROJECTPATH."/model/reflection/objects/Types/Name.php");
          return '\model\reflection\Types\meta\Name';
      }

  }
?>
