<?php namespace model\reflection\Types\meta;
  class Boolean extends \model\reflection\Meta\BaseMetadata
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

