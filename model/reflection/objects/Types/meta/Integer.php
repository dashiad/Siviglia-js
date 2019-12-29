<?php namespace model\reflection\Types\meta;

  class IntegerTypeException extends \model\reflection\Meta\BaseMetadataException {
      const ERR_TOO_SMALL=100;
      const ERR_TOO_BIG=101;
      const ERR_NOT_A_NUMBER=102;

      const TXT_TOO_SMALL='Valor demasiado pequeño';
      const TXT_TOO_BIG='Valor demasiado grande';
      const TXT_NOT_A_NUMBER='Debes introducir un número';

      const REQ_TOO_SMALL='MIN';
      const REQ_TOO_BIG='MAX';
  }
  class Integer extends \model\reflection\Meta\BaseMetadata
  {
      function __construct($def,$value=null)
      {
          BaseType::__construct($def,$value);
      }
      function validate($value)
      {
          if($value===null)
              return true;
          $value=trim($value);
          $res=BaseType::validate($value);
          if(!preg_match("/^(?:[0-9]+)+$/",$value))
              throw new IntegerTypeException(IntegerTypeException::ERR_NOT_A_NUMBER);

          if(isset($this->definition["MIN"]))
          {
              if($value < intval($this->definition["MIN"]))
                  throw new IntegerTypeException(IntegerTypeException::ERR_TOO_SMALL);
          }
          if(isset($this->definition["MAX"]))
          {
              if($value > intval($this->definition["MAX"]))
                throw new IntegerTypeException(IntegerTypeException::ERR_TOO_BIG);
          }
          return true;
      }
  }
