<?php namespace model\reflection\Types\meta;
  class AutoIncrement extends \model\reflection\Meta\BaseMetadata
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
