<?php namespace lib\storage\Mysql\types;


  class Integer extends BaseType
  {
      function serialize($name,$type,$serializer,$model=null)
      {
          if($type->hasValue())
          {
              return [$name=>$type->getValue()];
          }
          return [$name=>"NULL"];
      }

      function getSQLDefinition($name,$definition,$serializer)
      {
          $maxVal=io($definition,"MAX",null);
		  $minVal=io($definition,"MIN",null);
		  if(!$maxVal)
		  {
				$maxVal=(isset($definition["UNSIGNED"]) && $definition["UNSIGNED"])?4294967295:2147483647;
		  }
		  if(!$minVal)
				$minVal=0;

          if(isset($definition["UNSIGNED"]))
              $un=$definition["UNSIGNED"];
          else
          {
              if($minVal>=0)
                  $un=true;
          }

		  $type="";
		  if(($un && $maxVal<=255) || (!$un && $maxVal<=127 && $minVal>-128))
			$type="TINYINT";

			if(($un && $maxVal<=255) || (!$un && $maxVal<=127 && $minVal>-128))
				$type="TINYINT";
			if(($un && $maxVal<=65535) || (!$un && $maxVal<=32767 && $minVal>-32768))
				$type="SMALLINT";
			if(($un && $maxVal<=16777215) || (!$un && $maxVal<=8388607 && $minVal>-8388608))
				$type="MEDIUMINT";
			if(($un && $maxVal<=4294967295) || (!$un && $maxVal<=2147483647 && $minVal>-2147483648))
				$type="INT";
			if(!$type)
				$type="BIGINT";

          $default="";
          if(isset($definition["DEFAULT"]))
              $default=" DEFAULT ".$definition["DEFAULT"];
          return array("NAME"=>$name,"TYPE"=>$type.$default);
      }
  }
