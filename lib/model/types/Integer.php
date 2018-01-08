<?php namespace lib\model\types;

  class IntegerTypeException extends BaseTypeException {
      const ERR_TOO_SMALL=100;
      const ERR_TOO_BIG=101;
      const ERR_NOT_A_NUMBER=102;
  }
  class Integer extends BaseType
  {
      function __construct($def,$value=null)
      {
          BaseType::__construct($def,$value);          
      }
      function validate($value)
      {
          if($value===null)
              return true;
          $value=trim($value);
          $res=BaseType::validate($value);
          if(!preg_match("/^(?:[0-9]+)+$/",$value))
              throw new IntegerTypeException(IntegerTypeException::ERR_NOT_A_NUMBER);

          if(isset($this->definition["MIN"]))
          {
              if($value < intval($this->definition["MIN"]))
                  throw new IntegerTypeException(IntegerTypeException::ERR_TOO_SMALL);
          }
          if(isset($this->definition["MAX"]))
          {
              if($value > intval($this->definition["MAX"]))
                throw new IntegerTypeException(IntegerTypeException::ERR_TOO_BIG);
          }
          return true;
      }
  }  

   class IntegerHTMLSerializer extends BaseTypeHTMLSerializer
   {
      function serialize($type)
      {
          // Aqui habria que meter escapeado si la definition lo indica.
          if($type->hasValue())
              return htmlentities($type->getValue(),ENT_NOQUOTES,"UTF-8");
          return "";
      }
      function unserialize($type,$value)
      {
          if($value!==null && is_numeric($value))
          {
            $inted=intval($value);
            $type->setValue($inted);
          }
      }
   }

  class IntegerMYSQLSerializer extends BaseTypeMYSQLSerializer
  {
      function serialize($type)
      {
          if($type->hasValue())
          {
              return $type->getValue();
          }
          return "NULL";
      }

      function getSQLDefinition($name,$definition)
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

  class IntegerCASSSerializer extends BaseTypeCASSSerializer
  {
      function serialize($type)
      {
          if($type->valueSet)
              return $type->value?$type->value:'0';
          return "NULL";
      }

      function getCASSDefinition($name,$definition)
      {
          return array("NAME"=>$name,"TYPE"=>"IntegerType");          
      }      
  }
      
?>
