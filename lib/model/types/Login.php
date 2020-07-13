<?php namespace lib\model\types;
  class Login extends _String
  {
      function __construct($name,$def,$parentType=null, $value=null,$validationMode=null)
      {
          $def=array(
            "TYPE"=>"Login",
            "MINLENGTH"=>4,
            "MAXLENGTH"=>15,
            "REGEXP"=>'/^[a-z\d_]{3,15}$/i',
            "ALLOWHTML"=>false,
            "TRIM"=>true
          );
          parent::__construct($name,$def,$parentType, $value,$validationMode);
      }

  }
?>
