<?php namespace lib\storage\HTML\types;

  class AutoIncrement  extends BaseType
  {

      function unserialize($name,$type,$val,$serializer)
      {
          $value=$name[$val];
          if($value!==null && is_numeric($value)) {
              $inted=intval($value);
              $type->setValue($inted);
          }
      }
  }
