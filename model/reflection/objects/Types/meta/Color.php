<?php namespace model\reflection\Types\meta;
  class Color extends _String
  {
      function __construct($def,$value=false)
      {
          String::__construct(array("TYPE"=>"Color","MAXLENGTH"=>10),$value);
      }
  }
?>
