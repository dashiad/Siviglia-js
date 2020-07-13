<?php namespace lib\storage\HTML\types;

  class Date extends BaseType
  {
      function serialize($name,$type,$serializer,$model=null)
      {
          // Unserialize always will return UTC dates.

          switch($type->__definition["TIMEZONE"])
          {
          case "SERVER":
              {
                  $val= CDateType::serverToUTCDate($type->getValue());
                  return [$name=>$val];
              }break;
          case "UTC":
          case "CLIENT":
              {
                  return [$name=>$type->getValue()];

              }break;
          }
      }

      function unserialize($name,$type,$val,$serializer)
      {
          $value=$val[$name];
          if($value==null)
              return;

          if($value=='')
          {
             return $type->__clear();
          }

          // Unserialize a date coming from the client.
          // Obviously, basic syntax checking is done here.
          $parts=explode(" ",$value);
          @list($result["year"],$result["month"],$result["day"])=explode("-",$parts[0]);


          if(!checkdate($result["month"],$result["day"],$result["year"]))
              throw new BaseTypeException(BaseTypeException::ERR_INVALID);

          switch($type->__definition["TIMEZONE"])
          {
          case "SERVER":
              {
                  global $oCurrentUser;
                  $newVal=Date::offsetToLocalDate($value,$oCurrentUser->getUTCOffset());
                  $type->validate($newVal);
                  $type->setValue($newVal);
              }break;
          case "UTC":
              {
                  global $oCurrentUser;
                  $newVal=Date::offsetToUTCDate($value,$oCurrentUser->getUTCOffset());
                  $type->validate($newVal);
                  $type->setValue($newVal);
              }break;
          case "CLIENT":
          default:
              {
                  $type->validate($value);
                  $type->setValue($value);
              }
          }

      }

  }



?>
