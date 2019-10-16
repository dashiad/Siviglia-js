<?php namespace lib\storage\HTML\types;

  class DateTime extends BaseType
  {
      function serialize($name,$type,$serializer)
      {
          // Unserialize always will return UTC dates.

          switch($type->definition["TIMEZONE"])
          {
          case "SERVER":
              {
                  $val= DateTime::serverToUTCDate($type->getValue());
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
            $value=$val;
          if($value==null)
              return;

          if($value=='')
          {

             return $type->clear();
          }

          // Unserialize a date coming from the client.
          // Obviously, basic syntax checking is done here.
          $parts=explode(" ",$value);
          @list($result["year"],$result["month"],$result["day"])=explode("-",$parts[0]);
          if($parts[1])
              @list($result["hour"],$result["minutes"],$result["seconds"])=explode(":",$parts[1]);
          else
              @list($result["hour"],$result["minutes"],$result["seconds"])=array(0,0,0);


          if(!checkdate($result["month"],$result["day"],$result["year"]))
              throw new BaseTypeException(BaseTypeException::ERR_INVALID);

          switch($type->definition["TIMEZONE"])
          {
          case "SERVER":
              {
                  global $oCurrentUser;
                  $newVal=DateTime::offsetToLocalDate($value,$oCurrentUser->getUTCOffset());
                  $type->validate($newVal);
                  $type->setValue($newVal);
              }break;
          case "UTC":
              {
                  global $oCurrentUser;
                  $newVal=DateTime::offsetToUTCDate($value,$oCurrentUser->getUTCOffset());
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
