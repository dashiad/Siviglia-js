<?php namespace lib\model\types;
  class Name extends _String
  {
      function __construct($def,$value=false)
      {
          String::__construct(array("TYPE"=>"Name","MAXLENGTH"=>100),$value);
      }
      static function normalize($cad)
      {
          $cad=String::normalize($cad);
          return $cad;

      }
  }
?>
