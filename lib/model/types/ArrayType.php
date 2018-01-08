<?php
  namespace lib\model\types;
  class ArrayType extends BaseType implements \ArrayAccess
  {
      var $subTypeDef;
      const TYPE_NOT_MODIFIED_ON_NULL=0x20;
          
      function __construct($def,$neutralValue=null)
      {
          $this->subTypeDef=$def["ELEMENTS"];
          parent::__construct($def,$neutralValue);          
      }
      function getSubTypeDef()
      {
          return $this->subTypeDef;
      }
      function setValue($val)
      {
          if($val===null || !isset($val))
          {
              $this->valueSet=false;
              $this->value=null;
          }
          $this->valueSet=true;
          $this->value=$val;      
      }            
      function validate($value)
      {     
        if(!is_array($value))
                $value=array($value);                            
         $remoteType=TypeFactory::getType(null,$this->subTypeDef,null);
         for($k=0;$k<count($value);$k++)
         {
                if(!$remoteType->validate($value[$k]))
                        return false;
         }           
         return true;                                            
      }                      
      function getValue()
      {         
          if($this->valueSet)
            return $this->value; 
          if(isset($this->definition["DEFAULT"]))
            return explode(",",$this->definition["DEFAULT"]);
          return null;          
      }
      function count()
      {
          if($this->valueSet)
              return count($this->value);
          return false;
      }
      function __toString()
      {
         return implode(",",$this->value);              
      }

      function offsetExists($index)
      {
          if(!$this->valueSet)
              return false;
          return isset($this->value[$index]);        
      }

      function offsetGet($index)
      {
          return $this->value[$index];        
      }
      function offsetSet($index,$newVal)
      {
      }
      function offsetUnset($index)
      {

      }
  }

  class ArrayTypeHTMLSerializer
  {
      function serialize($type)
      {
          if($type->hasValue())return $type->getValue();
		  return "";
      }
      function unserialize($type,$value)
      {
          $type->validate($value);
          $type->setValue($value);
      }
  }

  class ArrayTypeMYSQLSerializer {
      var $typeSer;
      var $typeInstance;
      function getTypeSerializer($type)
      {
          if($this->typeSer!=null)
              return $this->typeSer;
          $this->typeInstance=TypeFactory::getType(null,$type->subTypeDef);
          $this->typeSer=TypeFactory::getSerializer($this->typeInstance,"MYSQL");
          return $this->typeSer;
      }
      function serialize($type)
      {         
         $remoteSerializer=$this->getTypeSerializer($type);
         $nItems=$type->count();
         if($nItems==0)
                return "NULL";
         for($k=0;$k<$nItems;$k++)         
         {
             // TODO : Esto aniade bastante carga..Habria que poder serializar por valor
             $val = $type->value;
             if (is_array($val)) {
                 $this->typeInstance->setValue($val[$k]);
             }
             else {
                 $this->typeInstance->setValue($val);
             }
             $results[]=$remoteSerializer->serialize($this->typeInstance);
         }
         return implode(",",$results);          
      }

      function unserialize($type,$value)
      {
          $type->setValue(explode(",",$value));          
      }
      function getSQLDefinition($name,$definition)
      {
          return array("NAME"=>$name,"TYPE"=>"TEXT");
      }
  }
