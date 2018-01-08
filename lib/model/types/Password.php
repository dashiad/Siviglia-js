<?php namespace lib\model\types;
  class Password extends _String
  {
      const DEFAULT_COST=12;
      function __construct($def=array(),$value=false)
      {     
          $def["TYPE"]="Password";   
          $def["MINLENGTH"]=io($def,"MINLENGTH",6);
          $def["MAXLENGTH"]=io($def,"MAXLENGTH",16);
          $def["REGEXP"]=io($def,"REGEXP",'/^[a-zA-Z0-9\d_]{'.$def["MINLENGTH"].','.$def["MAXLENGTH"].'}$/i');
          $def["TRIM"]=true;
          parent::__construct($def,$value);
      }
      function encode($configuration)
      {
          $passwordEncoding=io($configuration,"PASSWORD_ENCODING","default");
          $options=array("cost"=>io($configuration,"COST",Password::DEFAULT_COST));
          switch($passwordEncoding)
          {
              case "PLAINTEXT":{return;}break;
              case "BCRYPT":{
                  $type=PASSWORD_BCRYPT;}break;
              case "ARGON2I":{
                  $type=PASSWORD_ARGON2I;
              }break;
              default:{
                  $type=PASSWORD_DEFAULT;
              }
          }
          $this->value=password_hash($this->value,$type,$options);
      }

      function check($string)
      {
          return password_verify($string,$this->value);
      }
  }
?>
