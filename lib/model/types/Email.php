<?php namespace lib\model\types;
  class Email extends _String
  {
      function __construct($def,$value=false)
      {
            $def["MINLENGTH"]=8;
            $def["MAXLENGTH"]=50;
            $def["REGEXP"]='/^[^@]+@[a-zA-Z0-9._-]+\.[a-zA-Z]+$/';
            $def["ALLOWHTML"]=false;
            $def["TRIM"]=true;
            String::__construct($def,$value);
      }
  }
?>
