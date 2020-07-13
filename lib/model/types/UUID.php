<?php namespace lib\model\types;
include_once(LIBPATH."/model/types/BaseType.php");
class UUID extends BaseType
{
      var $valueSet=false;
      var $value;
      var $definition;
      var $name;

      function __construct($name,$def,$parentType=null, $value=null,$validationMode=null)
      {
          BaseType::__construct($name,$def,$parentType, $value,$validationMode);
          $this->setFlags(BaseType::TYPE_SET_ON_ACCESS);
      }

      function _validate($value)
      {
            return true;
      }
      function __hasValue()
      {
          return true;
      }
      function is_set()
      {
          return true;
      }
      function _setValue($v,$validationMode=null)
      {
          $this->value=$v;
          $this->valueSet=true;
      }
      function _getValue()
      {

          if($this->valueSet)
          {
            return $this->value;
          }
          include_once(LIBPATH."/model/types/libs/uuid.php");

          switch(intval($this->__definition["LEVEL"]))
          {
            default:
          case 1:
              {
                  $val= \lib\model\types\libs\UUID::uuid1();

              }break;
          case 3:
              {
                  $val= \lib\model\types\libs\UUID::uuid3(time(),UUID::nsDNS);
              }break;
          case 4:
              {
                  $val= \lib\model\types\libs\UUID::uuid4(time(),UUID::nsDNS);
              }break;
          case 5:
              {
                  $val= \lib\model\types\libs\UUID::uuid5(time(),UUID::nsDNS);
              }break;
          }
          $this->_setValue($val->__toString());
          return $this->value;
      }
      function _copy($ins)
      {
          $this->_setValue($ins->getValue());
      }
      function _equals($v)
      {
          return $this->valueSet && $this->value==$v;
      }
}
