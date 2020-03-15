<?php namespace model\reflection\Types\types\meta;
  class AutoIncrement extends \model\reflection\Meta
  {
      function getMeta()
      {
          return [
              "TYPE"=>"Container",
              "FIELDS"=>[
                  "TYPE"=>["TYPE"=>"String","FIXED"=>"AutoIncrement"],
                  "HELP"=>["LABEL"=>"Ayuda","TYPE"=>"Text","SET_ON_EMPTY"=>false],
              ]
          ];
      }
  }
