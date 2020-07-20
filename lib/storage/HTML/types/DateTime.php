<?php namespace lib\storage\HTML\types;

  class DateTime extends BaseType
  {
      function serialize($name,$type,$serializer,$model=null)
      {
          // Unserialize always will return UTC dates.

          switch($type->__definition["TIMEZONE"])
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

          if($value[$name]=='')
          {

             return $type->__clear();
          }

          // Unserialize a date coming from the client.
          // Obviously, basic syntax checking is done here.
          $parts=explode(" ",$value[$name]);
          @list($result["year"],$result["month"],$result["day"])=explode("-",$parts[0]);
          if(isset($parts[1]))
              @list($result["hour"],$result["minutes"],$result["seconds"])=explode(":",$parts[1]);
          else
              @list($result["hour"],$result["minutes"],$result["seconds"])=array(0,0,0);


          if(!checkdate($result["month"],$result["day"],$result["year"]))
              throw new \lib\model\types\BaseTypeException(\lib\model\types\BaseTypeException::ERR_INVALID);

          $timeZone=isset($type->__definition["TIMEZONE"])?$type->__definition["TIMEZONE"]:"CLIENT";
          switch($timeZone)
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
                  $type->validate($value[$name]);
                  $type->setValue($value[$name]);
              }
          }

      }

  }
