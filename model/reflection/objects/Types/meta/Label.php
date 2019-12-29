<?php namespace model\reflection\Types\meta;
  class Label extends _String
  {
      function __construct($def,$value=false)
      {
          String::__construct(array("TYPE"=>"Label","MAXLENGTH"=>50),$value);
      }                
  }
?>
