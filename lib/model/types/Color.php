<?php namespace lib\model\types;
  class Color extends _String
  {
      function __construct($def,$value=false)
      {
          String::__construct(array("TYPE"=>"Color","MAXLENGTH"=>10),$value);
      }
  }
?>
