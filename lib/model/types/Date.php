<?php namespace lib\model\types;

  class  DateTypeException extends BaseTypeException{
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
       function getValue()
      {
          if($this->valueSet)
            return $this->value;
          if(isset($this->definition["DEFAULT"]))
          {
              if($this->definition["DEFAULT"]=="NOW")
              {
                  $this->setAsNow();
                  return $this->value;
              }
          }
          return null;
      }
      function setAsNow()
      {
          $this->setValue(Date::getValueFromTimestamp());
      }

      function hasValue()
      {
          if(!$this->valueSet)
              return false;
          return $this->value!="" && $this->value!="0000-00-00";
      }

      function validate($value)
      {
          if (!$value && (!isset($this->definition["REQUIRED"]) || $this->definition["REQUIRED"]==false))
              return true;

          BaseType::validate($value);
          $asArr=$this->asArray($value);

          extract($asArr);
          if($day==0 && $month==0 && $year==0 && (!isset($this->definition["REQUIRED"]) || $this->definition["REQUIRED"]==false))
              return true;
          if(!checkdate($month,$day,$year))
              throw new BaseTypeException(BaseTypeException::ERR_INVALID);

          if(isset($this->definition["STARTYEAR"]))
          {
              if(intval($year)<intval($this->definition["STARTYEAR"]))
                  throw new DateTypeException(DateTypeException::ERR_START_YEAR,array("year"=>$this->definition["STARTYEAR"]));
          }
          if(isset($this->definition["ENDYEAR"]))
          {
              if(intval($year)>intval($this->definition["ENDYEAR"]))
                  throw new DateTypeException(DateTypeException::ERR_END_YEAR,array("year"=>$this->definition["ENDYEAR"]));
          }
          $timestamp=$this->getTimestamp($value,$asArr);
          $curTimestamp=time();
          if(isset($this->definition["STRICTLYPAST"]) && $curTimestamp < $timestamp)
              throw new DateTypeException(DateTypeException::ERR_STRICTLY_PAST);
          if(isset($this->definition["STRICTLYFUTURE"]) && $curTimestamp > $timestamp)
              throw new DateTimeException(DateTypeException::ERR_STRICTLY_FUTURE);

          BaseType::postValidate($value);

          return true;
      }

      function asArray($val=null)
      {
          if(!$val)$val=$this->value;

          $parts=explode(" ",$val);
          @list($result["year"],$result["month"],$result["day"])=explode("-",$parts[0]);
          return $result;
      }

      static function getValueFromTimestamp($timestamp=null) {
        return date(DateTime::DATE_FORMAT, $timestamp?$timestamp:time());
      }


      public function getTimestamp($value=null) {


          if(isset($this->definition["TIMEZONE"]) && $this->definition["TIMEZONE"]=="UTC")
          {
              $utcTz=new \DateTimeZone("UTC");
              $date=new \DateTime($value,$utcTz);
          }
          else
              $date = new \DateTime($value);

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
  }
