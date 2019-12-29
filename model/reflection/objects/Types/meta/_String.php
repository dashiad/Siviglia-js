<?php namespace model\reflection\Types\meta;

  class _StringException extends BaseTypeException {
      const ERR_TOO_SHORT=100;
      const ERR_TOO_LONG=101;
      const ERR_INVALID_CHARACTERS=102;
  }
  class _String extends BaseType
  {
      function __construct($def,$neutralValue=null)
      {
            BaseType::__construct($def,$neutralValue);
      }


      function validate($val)
      {
          if($val===null || !isset($val))
              return true;
        $res=BaseType::validate($val);
        if($res!==true)
            return $res;

         $len=strlen($val);
         if(isset($this->definition["MINLENGTH"]))
         {
            if($len < $this->definition["MINLENGTH"])
            {
                throw new _StringException(_StringException::ERR_TOO_SHORT);
            }
         }

         if(isset($this->definition["MAXLENGTH"]))
         {
            if($len > $this->definition["MAXLENGTH"])
                throw new _StringException(_StringException::ERR_TOO_LONG);

         }
         if(isset($this->definition["REGEXP"]))
         {
             if(!preg_match($this->definition["REGEXP"],$val))
             {
                throw new _StringException(_StringException::ERR_INVALID_CHARACTERS);
             }
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

