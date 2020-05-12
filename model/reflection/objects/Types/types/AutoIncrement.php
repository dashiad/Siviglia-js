<?php namespace model\reflection\Types\types;


  class AutoIncrement extends \lib\model\types\Container
  {
      function __construct($name,$parentType=null, $value=null,$validationMode=null){
parent::__construct($name, [
              "TYPE"=>"Container",
              "FIELDS"=>[
                  "LABEL"=>["LABEL"=>"Label","TYPE"=>"String"],
                  "TYPE"=>["LABEL"=>"Type","TYPE"=>"String","FIXED"=>"AutoIncrement"],
                  "HELP"=>["LABEL"=>"Ayuda","TYPE"=>"Text","KEEP_KEY_ON_EMPTY"=>false],
              ]
          ,$parentType,$value,$validationMode]);

      }
  }
