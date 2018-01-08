<?php namespace lib\model\types;
  class Label extends _String
  {
      function __construct($def,$value=false)
      {
          String::__construct(array("TYPE"=>"Label","MAXLENGTH"=>50),$value);
      }                
  }
?>
