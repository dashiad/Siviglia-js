<?php namespace lib\model\types;

// El tipo Relacion solo existe para poder "redireccionar" columnas de tipo Relationship, a su tipo padre.
class Relationship extends BaseType {
    function getRelationshipType()
      {          
          $obj=$this->definition["MODEL"];
          if(io($this->definition,"MULTIPLICITY","1:1")=="M:N")
          {
          
              $flist=$this->definition["FIELDS"]["REMOTE"];
              $remoteDef=\lib\model\types\TypeFactory::getObjectDefinition($obj);
            
              if(!$flist)
                $flist=$remoteDef["INDEXFIELDS"];
            
              foreach($flist as $key=>$value)
                $subTypes[$value]=\lib\model\types\TypeFactory::getRelationFieldTypeInstance($obj,$value);
                        
          } 
          else
          {

               if(isset($this->definition["FIELD"]))
                    $fields=(array)$this->definition["FIELD"];
               else
                    $fields=& $this->definition["FIELDS"];
          
               $flist=array_values($fields);
              $subTypes=array();
              for($k=0;$k<count($flist);$k++)
              {
                $subTypes[$flist[$k]]=\lib\model\types\TypeFactory::getRelationFieldTypeInstance($obj,$flist[$k]);
              }
          }
          if(count($subTypes)>1)
              return $subTypes;
          return $subTypes[$flist[0]];
      }
      function setValue($val)
      {
          // Las relaciones no permiten "" como valor de relacion.
          if($val==="")
          {
              return;
          }
          parent::setValue($val);
      }
}


class RelationshipHTMLSerializer extends BaseTypeHTMLSerializer
   {
      function serialize($type)
      {         
          $remoteType=$type->getRelationshipType();
          return \lib\model\types\TypeFactory::serializeType($remoteType,"HTML");
      }      
      function unserialize($type,$value)
      {   
          $remoteType=$type->getRelationshipType();
          \lib\model\types\TypeFactory::unserializeType($remoteType,$value,"HTML");          
          $type->setValue($remoteType->getValue());
      }
   }

  class RelationshipMYSQLSerializer extends BaseTypeMYSQLSerializer
  {
      function serialize($type)
      {   
          if(!$type->hasValue())      
              return 'NULL';
          $remoteType=$type->getRelationshipType();          
          $remoteType->setValue($type->getValue());
          $serialized= \lib\model\types\TypeFactory::serializeType($remoteType,"MYSQL");
          return $serialized;
      }      
      
      function unserialize($type,$value)
      {          
          $remoteType=$type->getRelationshipType();          
          \lib\model\types\TypeFactory::unserializeType($remoteType,$value,"MYSQL");
          $type->setValue($remoteType->getValue());          
      }
      function getSQLDefinition($name,$definition)
      {
          $remoteType=$type->getRelationshipType();
          $serializer=\lib\model\types\TypeFactory::getSerializer($remoteType,"MYSQL");
          return $serializer->getSQLDefinition($name,$remoteType->getDefinition());

      }
  }

