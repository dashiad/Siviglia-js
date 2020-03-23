<?php
  namespace model\reflection\Types\types;
include_once(__DIR__."/../BaseReflectionType.php");
  class _Array extends \model\reflection\Types\BaseReflectionType
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
                      "KEEP_KEY_ON_EMPTY"=>false
                  ],
                  "HELP"=>["LABEL"=>"Ayuda","TYPE"=>"Text","KEEP_KEY_ON_EMPTY"=>false],
                  "KEEP_KEY_ON_EMPTY"=>["LABEL"=>"Permitir valor vacío","TYPE"=>"Boolean","KEEP_KEY_ON_EMPTY"=>false]
              ]
          ];
      }
  }
