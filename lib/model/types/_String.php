<?php namespace lib\model\types;

  class _StringException extends BaseTypeException {
      const ERR_TOO_SHORT=100;
      const ERR_TOO_LONG=101;
      const ERR_INVALID_CHARACTERS=102;
      const ERR_EXCLUDED_VALUE=103;
      const TXT_TOO_SHORT="Minimum length is [%min%] characters";
      const TXT_TOO_LONG="Maximum length is [%max%] characters";
      const TXT_INVALID_CHARACTERS="Text contains invalid characters";
      const TXT_EXCLUDED_VALUE="This field cant have the value [%value%]";
  }
  class _String extends BaseType
  {
      function __construct($def,$neutralValue=null)
      {
            BaseType::__construct($def,$neutralValue);
      }
      function _setValue($val)
      {
          $this->value=$val;
          $this->valueSet=true;
      }
      function _getValue()
      {
          return $this->value;
      }
      function _equals($val)
      {
          return $this->value===$val;
      }
      function _copy($val){
          $this->value=$val->getValue();
      }
      function getMetaClassName()
      {
          include_once(PROJECTPATH."/model/reflection/objects/Types/meta/_String");
          return '\model\reflection\Types\meta\_String';
      }

      function _validate($val)
      {

         $len=strlen($val);
         if(isset($this->definition["MINLENGTH"]))
         {
            if($len < $this->definition["MINLENGTH"])
            {
                throw new _StringException(_StringException::ERR_TOO_SHORT,["min"=>$this->definition["MINLENGTH"]]);
            }
         }

         if(isset($this->definition["MAXLENGTH"]))
         {
            if($len > $this->definition["MAXLENGTH"])
                throw new _StringException(_StringException::ERR_TOO_LONG,["max"=>$this->definition["MAXLENGTH"]]);

         }
         if(isset($this->definition["REGEXP"]))
         {
             if(!preg_match($this->definition["REGEXP"],$val))
             {
                throw new _StringException(_StringException::ERR_INVALID_CHARACTERS);
             }
         }
         if(isset($this->definition["EXCLUDE"]))
         {
             if(in_array($val,$this->definition["EXCLUDE"]))
                 throw new _StringException(_StringException::ERR_EXCLUDED_VALUE,["value"=>$val]);
         }
         return true;
      }
      static function normalize($cad)
      {
          $cad=str_replace(array("á","é","í","ó","ú","Á","Ë","Í","Ó","Ú","Ñ"),array("a","e","i","o","u","a","e","i","o","u","ñ"),$cad);
          $cad=str_replace(array(".",",","-")," ",$cad);
          $cad=strtolower($cad);
          $cad=str_replace(array("#","_"),"",$cad);
          $cad=preg_replace("/  */"," ",$cad);
          return $cad;
      }
      static function correctEncoding($cad)
      {
          return \lib\php\Encoding::fixUTF8($cad);
      }
  }

