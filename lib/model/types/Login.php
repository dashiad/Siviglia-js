<?php namespace lib\model\types;
  class Login extends _String
  {
      function __construct($def,$value = null)
      {
          $def=array(
            "TYPE"=>"Login",
            "MINLENGTH"=>4,
            "MAXLENGTH"=>15,
            "REGEXP"=>'/^[a-z\d_]{3,15}$/i',
            "ALLOWHTML"=>false,
            "TRIM"=>true
          );
          parent::__construct($def,$value);
      }

      function getMetaClassName()
      {
          include_once(PROJECTPATH."/model/reflection/objects/Types/Login.php");
          return '\model\reflection\Types\meta\Login';
      }

  }
?>
