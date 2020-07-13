<?php namespace lib\storage\ES\types;


  class Integer extends BaseType
  {
      function serialize($name,$type,$serializer,$model=null)
      {
          if($type->__hasValue())
          {
              return array($name=>$type->getValue());
          }
          return null;
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
		  if(!$un && $maxVal<=127 && $minVal>-128)
			$type="byte";
            if($un && $maxVal<255)
                $type="short";
            else {
                if (!$un && $maxVal <= 32767 && $minVal > -32768)
                    $type = "short";
                else {
                    if ($un && $maxVal < pow(2, 32))
                        $type = "integer";
                    else {
                        if (!$un && $maxVal<=pow(2,32)-1 && $minVal>=-pow(2,32))
                            $type="integer";
                        else
                        {
                            $type="long";
                        }
                    }
                }
            }
          return array("NAME"=>$name,"TYPE"=>["type"=>$type]);
      }
  }
