<?php namespace lib\model\types;
  class Label extends _String
  {
      function __construct($name,$def,$parentType=null, $value=null,$validationMode=null)
      {
          parent::__construct($name,array("TYPE"=>"Label","MAXLENGTH"=>50),$parentType,$value,$validationMode);
      }


  }
?>
