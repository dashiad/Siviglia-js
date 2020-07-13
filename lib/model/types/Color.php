<?php namespace lib\model\types;
  class Color extends _String
  {
      function __construct($name,$def,$parentType=null, $value=null,$validationMode=null)
      {
          parent::__construct($name,array("TYPE"=>"Color","MAXLENGTH"=>10),$parentType,$value,$validationMode);
      }

  }
?>
