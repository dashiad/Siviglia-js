<?php namespace lib\model\types;

  class  DateException extends BaseTypeException{
      const ERR_START_YEAR=100;
      const ERR_END_YEAR=101;
      const ERR_STRICTLY_PAST=104;
      const ERR_STRICTLY_FUTURE=105;

      const TXT_START_YEAR="El año minimo permitido es %year%";
      const REQ_START_YEAR="STARTYEAR";

      const TXT_END_YEAR="El año maximo permitido es %year%";
      const REQ_END_YEAR="ENDYEAR";

      const TXT_STRICTLY_PAST="La fecha no puede ser futura";
      const REQ_STRICTLY_PAST="STRICTLYPAST";

      const TXT_STRICTLY_FUTURE="La fecha debe ser futura";
      const REQ_STRICTLY_FUTURE="STRICTLYFUTURE";
  }

  // Definitions of this class should always indicate:
  // "TIMEZONE"=>"UTC", "SERVER" or "CLIENT".If not set, it'll be "CLIENT", ie, no modification
  // Internal date representation IS ALWAYS IN THE FORMAT: YYYY-MM-DD HH:MM:SS.
  // DateTime types CANT BE UNIX timestamps, as there's no warranty about dates being > 1970-01-01
  // REMEMBER TO SET default-time-zone='UTC' in mysql if working with UTC dates
  // Importing timezones into mysql:mysql_tzinfo_to_sql /usr/share/zoneinfo | mysql -u root -p mysql

  class Date extends BaseType
  {
      const DATE_FORMAT="Y-m-d";
      function __construct($definition,$value=false)
      {
          BaseType::__construct($definition,$value);
      }
      function _getValue()
      {

            return $this->value;
      }
      function _setValue($v)
      {
          if($v=="NOW")
              $this->setAsNow();
          $asArr=$this->asArray($v);
          if($asArr!==false) {
              $this->value = $asArr["year"]."-".str_pad($asArr["month"],2,"0",STR_PAD_LEFT)."-".str_pad($asArr["day"],2,0,STR_PAD_LEFT);
              $this->valueSet = true;
          }
      }

      function setAsNow()
      {
          $this->setValue(Date::getValueFromTimestamp());
      }
      function __rawSet($val)
      {
          if($val=="NOW")
              $this->setAsNow();
          else {
              parent::__rawSet($val);
          }
      }

      function hasValue()
      {
          if(!$this->valueSet)
              return false;
          return $this->value!="" && $this->value!="0000-00-00";
      }

      function _validate($value)
      {

          if($value=="NOW")
              return true;

          $asArr=$this->asArray($value);

          extract($asArr);
          if($asArr===false)
              throw new BaseTypeException(BaseTypeException::ERR_INVALID,["val"=>$value],$this);

          if(isset($this->definition["STARTYEAR"]))
          {
              if(intval($year)<intval($this->definition["STARTYEAR"]))
                  throw new DateException(DateException::ERR_START_YEAR,array("val"=>$year,"year"=>$this->definition["STARTYEAR"]),$this);
          }
          if(isset($this->definition["ENDYEAR"]))
          {
              if(intval($year)>intval($this->definition["ENDYEAR"]))
                  throw new DateException(DateException::ERR_END_YEAR,array("val"=>$year,"year"=>$this->definition["ENDYEAR"]),$this);
          }
          $timestamp=$this->getTimestamp($value,$asArr);
          $curTimestamp=time();
          if(isset($this->definition["STRICTLYPAST"]) && $curTimestamp < $timestamp)
              throw new DateException(DateException::ERR_STRICTLY_PAST,["val"=>$value],$this);
          if(isset($this->definition["STRICTLYFUTURE"]) && $curTimestamp > $timestamp)
              throw new DateException(DateException::ERR_STRICTLY_FUTURE,["val"=>$value],$this);

          BaseType::postValidate($value);

          return true;
      }

      function asArray($val=null)
      {
          if(!$val)$val=$this->value;
          $v=date_parse($val);
          if($v===false)
              return $v;

              return [
                  "year"=>$v["year"],
                  "month"=>$v["month"],
                  "day"=>$v["day"]
              ];

      }

      static function getValueFromTimestamp($timestamp=null) {
        return date(Date::DATE_FORMAT, $timestamp?$timestamp:time());
      }


      public function getTimestamp($value=null) {

         try {
             if (isset($this->definition["TIMEZONE"]) && $this->definition["TIMEZONE"] == "UTC") {
                 $utcTz = new \DateTimeZone("UTC");
                 $date = new \DateTime($value, $utcTz);
             } else

                 $date = new \DateTime($value);
         }catch(\Exception $e)
         {
             throw new BaseTypeException(BaseTypeException::ERR_INVALID,["value"=>$value],$this);
         }

        $ret = $date->format("U");
        return ($ret < 0 ? 0 : $ret);
      }

      public static function offsetToLocalDate($date,$offset)
      {
          return CDateType::offsetToTimezoneDate($date,$offset,date_default_timezone_get());
      }

      public static function offsetToUTCDate($date,$offset)
      {
          return CDateType::offsetToTimezoneDate($date,$offset,"UTC");
      }
      public static function offsetToTimezoneDate($date,$offset,$timezone)
      {
          $gmtTz=new \DateTimeZone("UTC");
          // first, date + offset are converted to UTC.
          $srcDateTime=new \DateTime($date,$gmtTz);

          $secs=$srcDateTime->format('U')+$offset;
          $srcDateTime->setTimestamp($secs);

          if($timezone!="UTC")
          {
              $localTz=new \DateTimeZone($timezone);
              $localTime=new \DateTime($date,$localTz);
              $localOffset=$localTz->getOffset($localTime);
              $srcDateTime->setTimestamp($srcDateTime->format("U")+$localOffset);
          }
          return $srcDateTime->format(CDateType::DATE_FORMAT);
      }
      public static function serverToUTCDate($date=null)
      {
          if(!$date)
              $date=date(CDateType::DATE_FORMAT);

          $localTz=new \DateTimeZone(date_default_timezone_get());
          $utcTz=new \DateTimeZone("UTC");
          $mvDateTime=new \DateTime($date,$localTz);
          $offset=$localTz->getOffset($mvDateTime);
          return date(CDateType::DATE_FORMAT,$mvDateTime->format("U")-$offset);
      }
      function _copy($ins)
      {
          $this->value=$ins->value;
          $this->valueSet=$ins->valueSet;
      }
      function _equals($value)
      {
          return $this->value==$value;
      }
      function getMetaClassName()
      {
          include_once(PROJECTPATH."/model/reflection/objects/Types/Date.php");
          return '\model\reflection\Types\meta\Date';
      }

  }
