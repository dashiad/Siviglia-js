<?php namespace model\reflection\Types\types;
include_once(__DIR__."/../BaseReflectionType.php");
include_once(__DIR__."/BaseType.php");
  class _String extends \model\reflection\Types\BaseReflectionType
  {
      function getMeta()
      {
          return [
              "TYPE"=>"Container",
              "FIELDS"=>[
                  "TYPE"=>["TYPE"=>"String","FIXED"=>"String"],
                  "LABEL"=>["TYPE"=>"String"],
                  "MINLENGTH"=>["TYPE"=>"Integer","LABEL"=>"Longitud mínima","KEEP_KEY_ON_EMPTY"=>false],
                  "MAXLENGTH"=>["TYPE"=>"Integer","LABEL"=>"Longitud máxima","KEEP_KEY_ON_EMPTY"=>false],
                  "TRIM"=>["TYPE"=>"Boolean","DEFAULT"=>false,"LABEL"=>"Requerido","KEEP_KEY_ON_EMPTY"=>false],
                  "NORMALIZE"=>["TYPE"=>"Boolean","DEFAULT"=>false,"LABEL"=>"Requerido","KEEP_KEY_ON_EMPTY"=>false],
                  "REGEXP"=>["TYPE"=>"String","LABEL"=>"Expresión regular","KEEP_KEY_ON_EMPTY"=>false],
                  "REQUIRED"=>["TYPE"=>"Boolean","DEFAULT"=>false,"LABEL"=>"Requerido","KEEP_KEY_ON_EMPTY"=>false],
                  "HELP"=>["LABEL"=>"Ayuda","TYPE"=>"Text","KEEP_KEY_ON_EMPTY"=>false],
                  "KEEP_KEY_ON_EMPTY"=>["LABEL"=>"Permitir valor vacío","TYPE"=>"Boolean","KEEP_KEY_ON_EMPTY"=>false],
                  "FIXED"=>["TYPE"=>"String"],
                  "DEFAULT"=>["TYPE"=>"String"],
                  "SOURCE"=>BaseType::getSourceMeta()

              ]
          ];
      }
  }

