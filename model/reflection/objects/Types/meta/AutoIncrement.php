<?php namespace model\reflection\Types\meta;
  class AutoIncrement extends \model\reflection\Meta\BaseMetadata
  {
      function getMeta()
      {
          return [
              "TYPE"=>"Container",
              "FIELDS"=>[
                  "TYPE"=>["TYPE"=>"String","FIXED"=>"AutoIncrement"]
              ]
          ];
      }
  }
