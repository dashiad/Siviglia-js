<?php namespace model\reflection\Types\types;
  include_once(__DIR__."/../BaseReflectedType.php");
class _String extends \model\reflection\types\BaseReflectedType
  {
      function __construct($name,$parentType=null, $value=null,$validationMode=null){
        parent::__construct($name, "String",[
                  "TYPE"=>["LABEL"=>"Type","TYPE"=>"String","FIXED"=>"String"],
                  "MINLENGTH"=>["TYPE"=>"Integer","LABEL"=>"Longitud mínima","KEEP_KEY_ON_EMPTY"=>false],
                  "MAXLENGTH"=>["TYPE"=>"Integer","LABEL"=>"Longitud máxima","KEEP_KEY_ON_EMPTY"=>false],
                  "TRIM"=>["TYPE"=>"Boolean","DEFAULT"=>false,"LABEL"=>"Trim","KEEP_KEY_ON_EMPTY"=>false],
                  "NORMALIZE"=>["TYPE"=>"Boolean","DEFAULT"=>false,"LABEL"=>"Normalize","KEEP_KEY_ON_EMPTY"=>false],
                  "REGEXP"=>["TYPE"=>"String","LABEL"=>"Expresión regular","KEEP_KEY_ON_EMPTY"=>false]
              ],$parentType,$value,$validationMode);

      }
  }

