<?php namespace lib\model\types;
  class Boolean extends BaseType
  {
      function __construct($def,$val=null)
      {
          BaseType::__construct($def,$val);
      }
      function setValue($val)
      {

          BaseType::setValue($val);
      }
  }

