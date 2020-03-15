<?php
  namespace model\reflection\Types\types\meta;
  class _Array extends \model\reflection\Meta
  {
      function getMeta()
      {
          return [
              "TYPE"=>"Container",
              "FIELDS"=>[
                  "TYPE"=>["TYPE"=>"String","FIXED"=>"Array"],
                  "LABEL"=>["TYPE"=>"String","LABEL"=>"Label"],
                  "ELEMENTS"=>[
                      "LABEL"=>"Elementos",
                      "TYPE"=>"BASETYPE",
                      "REQUIRED"=>true
                  ],
                  "REQUIRED"=>[
                      "LABEL"=>"Requerido",
                      "TYPE"=>"Boolean",
                      "DEFAULT"=>false,
                      "SET_ON_EMPTY"=>false
                  ],
                  "HELP"=>["LABEL"=>"Ayuda","TYPE"=>"Text","SET_ON_EMPTY"=>false],
                  "SET_ON_EMPTY"=>["LABEL"=>"Permitir valor vacío","TYPE"=>"Boolean","SET_ON_EMPTY"=>false]
              ]
          ];
      }
  }
