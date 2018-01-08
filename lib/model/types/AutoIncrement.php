<?php namespace lib\model\types;
  class AutoIncrement extends Integer
  {
      function __construct($def,$value=null)
      {
          Integer::__construct(array("TYPE"=>"AutoIncrement","MIN"=>0,"MAX"=>9999999999),$value);
          $this->setFlags(BaseType::TYPE_SET_ON_SAVE);
      }
            
      function validate($value)
      {
          return true;
      }  
      function setValue($val)
      {          
          Integer::setValue($val);
      }
      function getRelationshipType()
      {
          return new Integer(array("MIN"=>0,"MAX"=>9999999999));
      }    
  }

  class AutoIncrementMYSQLSerializer extends BaseTypeMYSQLSerializer{
      function serialize($type)
      {
          if(!$type->hasValue())
              return NULL;
			return $type->value;
      }    
      function getSQLDefinition($name,$definition)
      {          
          $iSer=new IntegerMYSQLSerializer();
          $subDef=$iSer->getSQLDefinition($name,$definition);
          return array("NAME"=>$name,"TYPE"=>$subDef["TYPE"]." AUTO_INCREMENT");
      }
  }
  class AutoIncrementHTMLSerializer
  {
      
      function unserialize($type,$value)
      {
          if($value!==null && is_numeric($value)) {
              $inted=intval($value);
              $type->setValue($inted);
          }
      }
  }


   class AutoIncrementCASSSerializer extends BaseTypeCASSSerializer{
      function serialize($type)
      {        
          if(!$type->hasValue())
          {
              $val=microtime(true);
              $type->setValue($val);
          }
		  return $type->getValue();
      }    
      function unserialize($value)
      {
          return intval($value);
      }
      function getCASSDefinition($name,$definition)
      {
          return array("NAME"=>$name,"TYPE"=>"UTF8Type");              
      }
  }
?>
