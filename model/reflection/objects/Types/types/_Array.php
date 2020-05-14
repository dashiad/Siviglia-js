<?php
  namespace model\reflection\Types\types;

  include_once(__DIR__."/../BaseReflectedType.php");
class _Array extends \model\reflection\types\BaseReflectedType
  {
      function __construct($name,$parentType=null, $value=null,$validationMode=null){
        parent::__construct($name, "Array",[
                  "TYPE"=>["LABEL"=>"Type","TYPE"=>"String","FIXED"=>"Array"],
                  "ELEMENTS"=>[
                      "LABEL"=>"Elementos",
                      "TYPE"=>"/model/reflection/Model/types/TypeReference",
                      "REQUIRED"=>true
                  ],
              ],$parentType,$value,$validationMode);

      }
  }
