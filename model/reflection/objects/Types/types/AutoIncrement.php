<?php namespace model\reflection\Types\types;


  include_once(__DIR__."/../BaseReflectedType.php");
class AutoIncrement extends \model\reflection\types\BaseReflectedType
  {
      function __construct($name,$parentType=null, $value=null,$validationMode=null){
    parent::__construct($name, "AutoIncrement",[
                  "TYPE"=>["LABEL"=>"Type","TYPE"=>"String","FIXED"=>"AutoIncrement"]
              ],$parentType,$value,$validationMode);

      }
  }
