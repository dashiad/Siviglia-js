<?php namespace lib\model\types;  
  class _StringException extends BaseTypeException {
      const ERR_TOO_SHORT=100;
      const ERR_TOO_LONG=101;
      const ERR_INVALID_CHARACTERS=102;
  }
  class _String extends BaseType
  {
      function __construct($def,$neutralValue=null)
      {
            BaseType::__construct($def,$neutralValue);
      }
         
      
      function validate($val)
      {     
          if($val===null || !isset($val) || $val==='' || $val==='%')
              return true;
        $res=BaseType::validate($val);     
        if($res!==true)
            return $res;
        
         $len=strlen($val);
         if(isset($this->definition["MINLENGTH"]))
         {            
            if($len < $this->definition["MINLENGTH"])
            {
                throw new _StringException(_StringException::ERR_TOO_SHORT);
            }
         }
		 
         if(isset($this->definition["MAXLENGTH"]))
         {	 
            if($len > $this->definition["MAXLENGTH"])
                throw new _StringException(_StringException::ERR_TOO_LONG);
                
         }
         if(isset($this->definition["REGEXP"]))
         {
             if(!preg_match($this->definition["REGEXP"],$val))
             {
                throw new _StringException(_StringException::ERR_INVALID_CHARACTERS);
             }
         }                              
         return true;                                              
      }
      static function normalize($cad)
      {

          $cad=str_replace(array("á","é","í","ó","ú","Á","Ë","Í","Ó","Ú","Ñ"),array("a","e","i","o","u","a","e","i","o","u","ñ"),$cad);
          $cad=str_replace(array(".",",","-")," ",$cad);
          $cad=strtolower($cad);
          $cad=str_replace(array("#","_"),"",$cad);
          $cad=preg_replace("/  */"," ",$cad);
          return $cad;
      }
      static function correctEncoding($cad)
      {
          return \lib\php\Encoding::fixUTF8($cad);
      }
  }

  class _StringHTMLSerializer extends BaseTypeHTMLSerializer
   {
      function serialize($type)
      {
          // Aqui habria que meter escapeado si la definition lo indica.
          if($type->valueSet)
              return htmlentities($type->getValue(),ENT_NOQUOTES,"UTF-8");
          return "";
      }
      function unserialize($type,$value)
      {
          if($value!==null && $value!="NULL" && $value!="null")
          {
          // Habria que ver tambien si esta en UTF-8 
           if(isset($type->definition["TRIM"]))
               $value=trim($value);

            // Escapeado -- Anti-Xss?
            $type->validate($value);
            $type->setValue($value);

          }
      }
   }

  class _StringMYSQLSerializer extends BaseTypeMYSQLSerializer
  {
      function serialize($type)
      {
         $v= $type->hasValue()?"'".preg_replace('~[\x00\x0A\x0D\x1A\x22\x27\x5C]~u', '\\\$0', $type->getValue())."'":"NULL";
         return $v;
      }      
      function getSQLDefinition($name,$definition)
      {
          $defaultExpr="";
          if(isset($definition["DEFAULT"])) {
              $default = $definition["DEFAULT"];
              $defaultExpr = " DEFAULT '" . trim($default, "'") . "'";
          }
          $charSet=io($definition,"CHARACTER_SET","utf8");
          $collation=io($definition,"COLLATE","utf8_general_ci");
          $max=io($definition,"MAXLENGTH",45);
          return array("NAME"=>$name,"TYPE"=>"VARCHAR(".$max.") CHARACTER SET ".$charSet." COLLATE ".$collation." ".$defaultExpr);
      }
  }

  class _StringCASSSerializer extends BaseTypeCASSSerializer
  {
      function serialize($type)
      {		
          if($type->valueSet)
              return $type->value;
          return "NULL";
      }
      function unserialize($value)
      {
          return $value;
      }
      function getCASSDefinition($name,$definition)
      {
          return array("NAME"=>$name,"TYPE"=>"UTF8Type");
      }
  }
            
  

      
?>
