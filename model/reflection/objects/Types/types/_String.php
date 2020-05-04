<?php namespace model\reflection\Types\types;
  class _String extends \lib\model\types\Container
  {
      function __construct(){
        parent::__construct( [
              "LABEL"=>"String",
              "TYPE"=>"Container",
              "FIELDS"=>[
                  "TYPE"=>["LABEL"=>"Type","TYPE"=>"String","FIXED"=>"String"],
                  "LABEL"=>["LABEL"=>"Label","TYPE"=>"String"],
                  "MINLENGTH"=>["TYPE"=>"Integer","LABEL"=>"Longitud mínima","KEEP_KEY_ON_EMPTY"=>false],
                  "MAXLENGTH"=>["TYPE"=>"Integer","LABEL"=>"Longitud máxima","KEEP_KEY_ON_EMPTY"=>false],
                  "TRIM"=>["TYPE"=>"Boolean","DEFAULT"=>false,"LABEL"=>"Trim","KEEP_KEY_ON_EMPTY"=>false],
                  "NORMALIZE"=>["TYPE"=>"Boolean","DEFAULT"=>false,"LABEL"=>"Normalize","KEEP_KEY_ON_EMPTY"=>false],
                  "REGEXP"=>["TYPE"=>"String","LABEL"=>"Expresión regular","KEEP_KEY_ON_EMPTY"=>false],
                  "REQUIRED"=>["TYPE"=>"Boolean","DEFAULT"=>false,"LABEL"=>"Requerido","KEEP_KEY_ON_EMPTY"=>false],
                  "HELP"=>["LABEL"=>"Ayuda","TYPE"=>"Text","KEEP_KEY_ON_EMPTY"=>false],
                  "KEEP_KEY_ON_EMPTY"=>["LABEL"=>"Permitir valor vacío","TYPE"=>"Boolean","KEEP_KEY_ON_EMPTY"=>false],
                  "FIXED"=>["TYPE"=>"String","LABEL"=>"Valor Fijo","KEEP_ON_EMPTY"=>false],
                  "DEFAULT"=>["TYPE"=>"String","LABEL"=>"Valor por defecto","KEEP_ON_EMPTY"=>false],
                  "SOURCE"=>\model\reflection\Types::getSourceMeta()

              ]
          ]);

      }
  }

