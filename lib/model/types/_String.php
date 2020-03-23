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
          if(isset($this->definition["TRIM"]) && $this->definition["TRIM"]==true)
          {
                $val=trim($val);
          }
          if(isset($this->definition["NORMALIZE"]) && $this->definition["NORMALIZE"]==true)
          {
              $val=_String::normalize($val);
          }
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
          $this->setValue($val->getValue());
      }
      function getMetaClassName()
      {
          include_once(PROJECTPATH."/model/reflection/objects/Types/_String");
          return '\model\reflection\Types\meta\_String';
      }

      function _validate($val)
      {

         $len=strlen($val);
         if(isset($this->definition["MINLENGTH"]))
         {
            if($len < $this->definition["MINLENGTH"])
            {
                throw new _StringException(_StringException::ERR_TOO_SHORT,["val"=>$len,"min"=>$this->definition["MINLENGTH"]],$this);
            }
         }

         if(isset($this->definition["MAXLENGTH"]))
         {
            if($len > $this->definition["MAXLENGTH"])
                throw new _StringException(_StringException::ERR_TOO_LONG,["val"=>$len,"max"=>$this->definition["MAXLENGTH"]],$this);

         }
         if(isset($this->definition["REGEXP"]))
         {
             if(!preg_match($this->definition["REGEXP"],$val))
             {
                throw new _StringException(_StringException::ERR_INVALID_CHARACTERS,null,$this);
             }
         }
         if(isset($this->definition["EXCLUDE"]))
         {
             if(in_array($val,$this->definition["EXCLUDE"]))
                 throw new _StringException(_StringException::ERR_EXCLUDED_VALUE,["value"=>$val],$this);
         }

         return true;
      }
      static function normalize($cad)
      {
          $cad=str_replace(array("á","é","í","ó","ú","Á","É","Í","Ó","Ú","Ñ"),array("a","e","i","o","u","a","e","i","o","u","ñ"),$cad);
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
      function getEmptyValue()
      {
          return "";
      }
  }

