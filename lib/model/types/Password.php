<?php namespace lib\model\types;
  class Password extends _String
  {
      const DEFAULT_COST=12;
      var $encoded;
      function __construct($def=array(),$value=false)
      {
          $def["TYPE"]="Password";
          $def["MINLENGTH"]=io($def,"MINLENGTH",6);
          $def["MAXLENGTH"]=io($def,"MAXLENGTH",16);
          $def["REGEXP"]=io($def,"REGEXP",'/^[a-zA-Z0-9\d_]{'.$def["MINLENGTH"].','.$def["MAXLENGTH"].'}$/i');
          $def["TRIM"]=true;
          $this->encoded=false;
          parent::__construct($def,$value);
      }
      function _validate($val)
      {

          if(strpos($val,'$argon2i$')===0 ||
              strpos($val,'$2y$')===0)
              return true;
          return parent::_validate($val);
      }
      function _setValue($val)
      {
          $this->value=$val;
          // Los sistemas de encriptacion ponen como primer caracter el $. Como $ no es valido
          // en teoria deberia servir.
          if(preg_match('/^$[0-9]+$[0-9]+$/',$val))
              $this->encoded=true;
          else
              $this->encoded=false;
          $this->valueSet=true;
      }

      function encode($str=null)
      {
          if($this->encoded && $str==null)
              return $this->value;


          $passwordEncoding=io($this->definition,"PASSWORD_ENCODING","BCRYPT");
          $options["cost"]=io($this->definition,"COST","12");

          switch($passwordEncoding)
          {
              case "PLAINTEXT":{return $str?$str:$this->value;}break;
              case "BCRYPT":{
                  $type=PASSWORD_BCRYPT;}break;
              case "ARGON2I":{
                  $type=PASSWORD_ARGON2I;
              }break;
              default:{
                  $type=PASSWORD_DEFAULT;
              }
          }
          $this->encoded=true;
          $peppered=$this->preEncode($str?$str:$this->value);
          $returned=password_hash($peppered,$type,$options);
          if(!$str) {
              $this->value = $returned;
              $this->encoded = true;
          }
          return $returned;
      }
      function preEncode($value)
      {
          global $Config;
          $pepper="7092kadsfpa030(/%&/(32";
          if(isset($Config) && isset($Config["RANDOM_STR"]))
              $pepper=$Config["RANDOM_STR"];
          return hash_hmac("sha256",$value,$pepper);
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
          $enc=$this->preEncode($string);
          return password_verify($enc,$this->value);
      }

      function getMetaClassName()
      {
          include_once(PROJECTPATH."/model/reflection/objects/Types/Password.php");
          return '\model\reflection\Types\meta\Password';
      }
  }
