<?php namespace model\reflection\Types\meta;


  class _String extends \model\reflection\Meta\BaseMetadata
  {
      function getMeta()
      {
          return [
              "TYPE"=>"Container",
              "FIELDS"=>[
                  "TYPE"=>["TYPE"=>"String","FIXED"=>"String"],
                  "MINLENGTH"=>["TYPE"=>"Integer","LABEL"=>"Longitud mínima","SET_ON_EMPTY"=>false],
                  "MAXLENGTH"=>["TYPE"=>"Integer","LABEL"=>"Longitud máxima","SET_ON_EMPTY"=>false],
                  "TRIM"=>["TYPE"=>"Boolean","DEFAULT"=>false,"LABEL"=>"Requerido","SET_ON_EMPTY"=>false],
                  "NORMALIZE"=>["TYPE"=>"Boolean","DEFAULT"=>false,"LABEL"=>"Requerido","SET_ON_EMPTY"=>false],
                  "REGEXP"=>["TYPE"=>"String","LABEL"=>"Expresión regular","SET_ON_EMPTY"=>false],
                  "REQUIRED"=>["TYPE"=>"Boolean","DEFAULT"=>false,"LABEL"=>"Requerido","SET_ON_EMPTY"=>false],
                  "HELP"=>["LABEL"=>"Ayuda","TYPE"=>"Text","SET_ON_EMPTY"=>false],
                  "SET_ON_EMPTY"=>["LABEL"=>"Permitir valor vacío","TYPE"=>"Boolean","SET_ON_EMPTY"=>false],
                  "FIXED"=>["TYPE"=>"String"],
                  "DEFAULT"=>["TYPE"=>"String"],
                  "SOURCE"=>BaseType::getSourceMeta(),

              ]
          ];
      }
  }

