<?php namespace model\reflection\Types\meta;
include_once(LIBPATH."/model/types/BaseType.php");
class UUID extends \model\reflection\Meta\BaseMetadata
{
      var $valueSet=false;
      var $value;
      var $definition;
      var $name;

      function __construct($definition,$value=null)
      {
          BaseType::__construct($definition,$value);
          $this->setFlags(BaseType::TYPE_SET_ON_ACCESS);
      }

      function validate($value)
      {
            return true;
      }
      function hasValue()
      {
          return true;
      }
      function is_set()
      {
          return true;
      }

      function getValue()
      {

          if($this->valueSet)
          {
            return $this->value;
          }
          include_once(LIBPATH."/model/types/libs/uuid.php");

          switch(intval($this->definition["LEVEL"]))
          {
            default:
          case 1:
              {
                  $val= \model\reflection\Types\meta\libs\UUID::uuid1();

              }break;
          case 3:
              {
                  $val= \model\reflection\Types\meta\libs\UUID::uuid3(time(),UUID::nsDNS);
              }break;
          case 4:
              {
                  $val= \model\reflection\Types\meta\libs\UUID::uuid4(time(),UUID::nsDNS);
              }break;
          case 5:
              {
                  $val= \model\reflection\Types\meta\libs\UUID::uuid5(time(),UUID::nsDNS);
              }break;
          }

          $this->setValue($val->__toString());
          return $this->value;
      }

}
