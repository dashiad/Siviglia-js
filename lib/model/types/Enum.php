<?php
namespace lib\model\types;
// El valor de una clase enumeracion es el indice.El "texto" de la enumeracion es la label.
class Enum extends BaseType
  {
      function __construct($def,$neutralValue=null)
      {
            BaseType::__construct($def,$neutralValue);
      }               
      function validate($val)
      {   
          if($val===null || !isset($val) || $val==='')
          {
              return true;
          }
          if(!is_numeric($val))
          {
              if(!in_array($val,$this->definition["VALUES"]))
              {
                  _d($this->definition);
                  _d($val);
                  debug_trace();
                  throw new BaseTypeException(BaseTypeException::ERR_INVALID);
              }
               
              $val=array_search($val,$this->definition["VALUES"]);
          }
          

          if(!isset($this->definition["VALUES"][$val]))
             throw new BaseTypeException(BaseTypeException::ERR_INVALID);
          return true;
      }

      function setValue($val)
      {
          if(!$this->validate($val))
          {
              return;
          }
          if($val===null || !isset($val))
          {
              $this->valueSet=false;
              $this->value=null;
              return;
          }
          $this->valueSet=true;
          if(!is_numeric($val))
          {
              $this->value=array_search($val,$this->definition["VALUES"]);
          }
          else
              $this->value=intval($val);
      }
      function getLabels()
      {
          return $this->definition["VALUES"];
      }
      function getDefaultValue()
      {
          if(isset($this->definition["DEFAULT"]))
          {
              return $this->getValueFromLabel($this->definition["DEFAULT"]);
          }
          return null;
      }
      function getValueFromLabel($label)
      {
          return array_search($label,$this->definition["VALUES"]);
      }
      function getLabelFromValue($value)
      {
          return $this->definition["VALUES"][$value];
      }
      function getLabel()
      {
          if(!$this->hasOwnValue())
          {
              if($this->hasDefaultValue())
                 return $this->definition["DEFAULT"];
              return "";
          }    
          return $this->definition["VALUES"][$this->value];
      }
      function equals($value)
      {
          if($this->value===null || $value===null)
              return $this->value===$value;
          return ((string)$this->value==(string)$value);
      }
  }

  class EnumHTMLSerializer extends BaseTypeHTMLSerializer
   {
      function serialize($type)
      {
          // Aqui habria que meter escapeado si la definition lo indica.
          if($type->hasValue())
              return htmlentities($type->getLabel(),ENT_NOQUOTES,"UTF-8");
          return "";
      }

      function unserialize($type,$value)
      {
          if($value===null)
              return;
          $val=is_numeric($value)?intval($value):$value;
          $type->validate($val);
          $type->setValue($val);          
          return $value;
      }
   }

  class EnumMYSQLSerializer extends BaseTypeMYSQLSerializer
  {
      function serialize($type)
      {         
          if($type->hasValue())
          {
              $def=$type->getDefinition();
              if(isset($def["MYSQL_STORE_AS_INTEGER"]))
                  return intval($type->getValue());
              return "'".htmlentities($type->getLabel(),ENT_NOQUOTES,"UTF-8")."'";
          }
          return "NULL";
      }
      function unserialize($type,$value)
      {
          $type->setValue($value);
      }
      function getSQLDefinition($name,$definition)
      {
          $default=$definition["DEFAULT"];
          if(!$definition["VALUES"])
              var_dump($definition);
          return array("NAME"=>$name,"TYPE"=>"ENUM('".implode("','",$definition["VALUES"])."') ".(isset($default)?"DEFAULT '".$default."'":""));
      }
  }

  class EnumCASSSerializer extends BaseTypeCASSSerializer
  {
      function serialize($type)
      {		
          if($type->hasValue())
              return $type->getValue();
          return "NULL";
      }
      function unserialize($value)
      {
          return intval($value);
      }
      function getCASSDefinition($name,$definition)
      {
          return array("NAME"=>$name,"TYPE"=>"IntegerType");
      }
  }
