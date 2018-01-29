<?php namespace lib\model\types;
include_once(LIBPATH."/model/types/BaseType.php");
  class TypeFactory
  {
      static $serializers=array();
      static function includeType($type,$suffix="")
      {          
          if($type[0]=='\\')
              return $type;
          if($type=="String")
              $type="_String";
          $cName='\lib\model\types\\'.$type;
          if(is_file(LIBPATH."/model/types/".$type.".php"))
          {
              include_once(LIBPATH."/model/types/".$type.".php");
          }
          else
          {
                         
             // $cName='\app\model\types\\'.$type;
             // if(!class_exists($cName))
             // {
              //echo $type;
                  throw new BaseTypeException(BaseTypeException::ERR_TYPE_NOT_FOUND,array("name"=>$type));
             // }
          }
          return $cName;
      }		
      static function getObjectLayer($objName)
      {
          $classR=new \ReflectionClass($objName);
          $namespace=$classR->getNamespaceName();
          $pos=strpos($namespace,'\\');
          if($pos===false)
              return $namespace;
          return substr($namespace,0,$pos);
      }
      // The $object param is either a model instance, a model name (string), or null.
      // It's required, in case of inherited models.
      static function getType($object,$def,$value=null)
      {    
          try
          {
          
            if(!isset($def["TYPE"]))
            {
            
                if(isset($def["REFERENCES"]))
                {
                    // Important: The object parameter keeps pointing to the original
                    // object.
                    return TypeFactory::getType($object,$def["REFERENCES"],$value);
                }

                if($def["MODEL"])
                {

                    $newType=TypeFactory::getType(null,TypeFactory::getObjectField($def["MODEL"],$def["FIELD"]),$value);
                    // En caso de que la referencia defina un DEFAULT, sobreescribe al que llega de la definicion de tipo.
                    if(isset($def["DEFAULT"]) && !empty($def["DEFAULT"]))
                    {
                        $newType->setDefaultValue($def["DEFAULT"]);
                    }
                    return $newType;
                }


                  throw new BaseTypeException(BaseTypeException::ERR_TYPE_NOT_FOUND,array("def"=>$def));
            }
            $type=$def["TYPE"];                      
            $targetType=TypeFactory::includeType($type);
            $t= new $targetType($def,$value);
            return $t;
          }
          catch(\Exception $e)
          {
              if($object==null)
                  throw($e);
              if(is_string($object))
              {
                  $def=TypeFactory::getObjectDefinition($object);
              }
              else
                  $def=$object->getDefinition();
              debug($def);
              if($def["EXTENDS"])
                      return TypeFactory::getType($def["EXTENDS"],$def,$value);              
          }
      }
      

      static function getObjectField($objectName,$fieldName)
      {

          $def=\lib\model\types\TypeFactory::getObjectDefinition($objectName);
          if($def["FIELDS"][$fieldName])
          {
              return $def["FIELDS"][$fieldName];
          }
          if($def["ALIASES"][$fieldName])
              return $def["ALIASES"][$fieldName];
          if(strpos($fieldName,"/")!==false)
          {
              $model=\lib\model\BaseModel::getModelInstance($objectName);
              return $model->__getField($fieldName)->getDefinition();
          }
          if($def["EXTENDS"])
              return TypeFactory::getObjectField($def["EXTENDS"],$fieldName);
      }



      static function getAliasField($objectName,$fieldName)
      {
          $def=\lib\model\types\TypeFactory::getObjectDefinition($objectName);
          return $def["ALIAS"][$fieldName];
      }

      static function getFieldTypeInstance($objectName,$fieldName)
      {
          $definition=\lib\model\types\TypeFactory::getObjectField($objectName,$fieldName);
          return TypeFactory::getType(null,$definition);
      }

      static function getRelationFieldTypeInstance($objectName,$fieldName)
      {                    
          $type=\lib\model\types\TypeFactory::getFieldTypeInstance($objectName,$fieldName);
          return $type->getRelationshipType();
      }

       static function getObjectDefinition($objName,$layer=null)
      {

          if(!is_object($objName) || get_class($objName)!= 'model\reflection\Model\ModelName')
          {
              if(trim($objName)=="")
              {
                  print_r(debug_backtrace());          
              }
              $objName=new \model\reflection\Model\ModelName($objName,$layer);
          }

          $objName->getDefinition();
          $className=$objName->getNamespaced().'\Definition';
          if(!class_exists($className))
          {
              throw new BaseTypeException(BaseTypeException::ERR_TYPE_NOT_FOUND,array("name"=>$className));
          }
          // Se instancia, por si hay que hacer inicializacion de la definicion en el constructor.
          $n=new $className();
          return $n->getDefinition();
      }

      static function getSerializer($mixedType,$serializer)
      {
          if(is_object($mixedType))
          {
              $className=get_class($mixedType);
              $type=substr($className,strrpos($className,'\\')+1);
          }
          else
              $type=$mixedType;

          if(is_object($serializer))
              $serializerType=$serializer->getSerializerType();
          else
              $serializerType=$serializer;

          if(isset(\lib\model\types\TypeFactory::$serializers[$serializerType][$type])) {
              $cachedType = \lib\model\types\TypeFactory::$serializers[$serializerType][$type];
              if ($cachedType)
                  return new $cachedType();
          }
          
          $type=TypeFactory::includeType($type);
          
          $name=$type;
          
          $typeList=array_values(class_parents($name));
          $nEls=array_unshift($typeList,$name);

          for($k=0;$k<$nEls;$k++)
          {
              if($typeList[$k]=='lib\model\types\BaseType')
                  break;
              $sName=$typeList[$k].$serializerType."Serializer";
              if(@class_exists($sName))              
              {              
                  TypeFactory::$serializers[$serializerType][$type]=$sName;
                  return new $sName();
              }              
          }

          clean_debug_backtrace(4);
          throw new BaseTypeException(BaseTypeException::ERR_SERIALIZER_NOT_FOUND,array("name"=>$type,"serializer"=>$serializer));
          
      }
	  
      static function serializeType($type,$serializerType)
      {
          $typeName='\\'.get_class($type);
          $serObj=\lib\model\types\TypeFactory::getSerializer($typeName,$serializerType);
          $res=$serObj->serialize($type);          
          return $res;
      }
      static function unserializeType($type,$value,$serializerType)
      {
          if(!$type)
          {
              throw new BaseTypeException(BaseTypeException::ERR_TYPE_NOT_FOUND,array("name"=>$type));
          }

          $typeName='\\'.get_class($type);          
          $serObj=\lib\model\types\TypeFactory::getSerializer($typeName,$serializerType);
          $serObj->unserialize($type,$value);
      }
      static function unserializeArray($definition,$arr,$serializerType)
      {
          $result=array();
          foreach($definition["FIELDS"] as $key=>$value)
          {
              $result[$key]=TypeFactory::getType(null,$value,null);
              if(isset($arr[$key]))
              {
                  $typeName='\\'.get_class($result[$key]);
                  $serObj=\lib\model\types\TypeFactory::getSerializer($typeName,$serializerType);
                  $serObj->unserialize($result[$key],$value);
              }
          }
          return $result;
      }
     
	  
	  static function serialize($object,$serializerType,$fieldList=null)
	  {
	  $definition=& $object->__getObjectDefinition();
	  if(!$fieldList)
		   $fieldList=array_keys($definition["FIELDS"]);
	  
		$nFields=count($fieldList);
		   for($k=0;$k<$nFields;$k++)
		   {								
				$typeObj=\lib\model\types\TypeFactory::getType(null,$fieldList[$k],$curField);				
				$result[$fieldList[$k]]=\lib\model\types\TypeFactory::serializeType($object->__data[$fieldList[$k]],$serializerType);
		   }	
			
		   return $result;	
	  }
      static function isSameField($definition,$model,$value)
      {
          if($definition["MODEL"]==$model)
          {
              if($definition["FIELD"]==$value)
                return true;
              return false;
          }
          $def=TypeFactory::getObjectField($definition["MODEL"],$definition["FIELD"]);
          if($def && $def["TYPE"]=="Relationship")
          {
              if($def["MODEL"]==$model)
              {
                  $vals=array_values($def["FIELDS"]);
                  if($vals[0]==$value && count($vals)==1)
                      return true;
              }
          }
          return false;

      }
  }
?>
