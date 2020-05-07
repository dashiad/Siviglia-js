<?php
  namespace model\reflection\Types\types;

  class _Array extends \lib\model\types\Container
  {
      function __construct(){
parent::__construct( [
              "LABEL"=>"Array",
              "TYPE"=>"Container",
              "FIELDS"=>[
                  "TYPE"=>["LABEL"=>"Type","TYPE"=>"String","FIXED"=>"Array"],
                  "LABEL"=>["TYPE"=>"String","LABEL"=>"Label"],
                  "ELEMENTS"=>[
                      "LABEL"=>"Elementos",
                      "TYPE"=>"/model/reflection/Model/types/TypeReference",
                      "REQUIRED"=>true
                  ],
                  "REQUIRED"=>[
                      "LABEL"=>"Requerido",
                      "TYPE"=>"Boolean",
                      "DEFAULT"=>false,
                      "KEEP_KEY_ON_EMPTY"=>false
                  ],
                  "HELP"=>["LABEL"=>"Ayuda","TYPE"=>"Text","KEEP_KEY_ON_EMPTY"=>false],
                  "KEEP_KEY_ON_EMPTY"=>["LABEL"=>"Permitir valor vacío","TYPE"=>"Boolean","KEEP_KEY_ON_EMPTY"=>false],
                  "SOURCE"=>\model\reflection\Types::getSourceMeta()
              ]
          ]);

      }
  }
