<?php namespace model\reflection\Types\types;
include_once(__DIR__."/../BaseReflectionType.php");
include_once(__DIR__."/BaseType.php");
  class AutoIncrement extends \model\reflection\Types\BaseReflectionType
  {
      function getMeta()
      {
          return [
              "TYPE"=>"Container",
              "FIELDS"=>[
                  "LABEL"=>["TYPE"=>"String"],
                  "TYPE"=>["TYPE"=>"String","FIXED"=>"AutoIncrement"],
                  "HELP"=>["LABEL"=>"Ayuda","TYPE"=>"Text","KEEP_KEY_ON_EMPTY"=>false],
              ]
          ];
      }
  }
