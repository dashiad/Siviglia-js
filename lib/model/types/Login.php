<?php namespace lib\model\types;
  class Login extends _String
  {
      function __construct($def,$value = -1)
      {
          $def=array(
            "TYPE"=>"Login",
            "MINLENGTH"=>4,
            "MAXLENGTH"=>15,
            "REGEXP"=>'/^[a-z\d_]{3,15}$/i',
            "ALLOWHTML"=>false,
            "TRIM"=>true
          );          
          String::__construct($def,$value);                      
      }       
  }
?>
