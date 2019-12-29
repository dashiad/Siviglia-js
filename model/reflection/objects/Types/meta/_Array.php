<?php
  namespace model\reflection\Types\meta;
  class _Array extends \model\reflection\Meta\BaseMetadata
  {
      function getMeta()
      {
          return [
              "TYPE"=>"Container",
              "FIELDS"=>[
                  "TYPE"=>["TYPE"=>"String","FIXED"=>"Array"],
                  "ELEMENTS"=>[
                      "TYPEREF"=>"BASETYPE"
                  ],
                  "REQUIRED"=>[
                      "TYPE"=>"Boolean",
                      "DEFAULT"=>false
                  ]
              ]
          ];
      }
  }
