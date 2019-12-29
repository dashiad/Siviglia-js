<?php namespace model\reflection\Types\meta;
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
      function encode($configuration=null)
      {
          if(!$configuration)
          {
              $passwordEncoding=$this->definition["PASSWORD_ENCODING"];
              $options=array("cost"=>$this->definition["COST"]);
          }
          else {
              $passwordEncoding = io($configuration, "PASSWORD_ENCODING", "default");
              $options = array("cost" => io($configuration, "COST", Password::DEFAULT_COST));
          }
          if(!$passwordEncoding)
              $passwordEncoding="BCRYPT";
          if(!$options["cost"])
              $options["cost"]=10;
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
      function setRandomValue()
      {
          $length=6;
          $str = 'abcdefghijkmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
          for ($i = 0, $passwd = ''; $i < $length; $i++)
                  $passwd .= substr($str, mt_rand(0, strlen($str) - 1), 1);
          $this->setValue($passwd);
          return $passwd;
      }


      function check($string)
      {
          return password_verify($string,$this->value);
      }
  }
class PasswordMeta extends \model\reflection\Types\meta\BaseTypeMeta
  {
      function getMeta($type)
      {
          $def=$type->getDefinition();
          unset($def["SALT"]);
          return $def;
      }
  }
