<?php namespace lib\model\types;
  class Email extends _String
  {
      function __construct($name,$def,$parentType=null, $value=null,$validationMode=null)
      {
            $def["MINLENGTH"]=8;
            $def["MAXLENGTH"]=50;
            $def["REGEXP"]='/^[^@]+@[a-zA-Z0-9._-]+\.[a-zA-Z]+$/';
            $def["ALLOWHTML"]=false;
            $def["TRIM"]=true;
            _String::__construct($name,$def,$parentType,$value,$validationMode);
      }

  }
?>
