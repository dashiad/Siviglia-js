<?php namespace model\reflection\Types\meta;
include_once(LIBPATH."/model/types/BaseType.php");
  class TypeFactory
  {
      static $typeProviders=array();
      static $typeProviderCache=array();

      static function includeType($type,$suffix="")
      {

          $parts=explode('\\',$type);
          $n=count($parts);
          if($n>1)
          {
              if($parts[0]!="lib")
              {
                  return $type;
              }
              $type=$parts[$n-1];
          }

          $type=ucfirst(strtolower($type));
          if($type=="String" || $type=="_string")
              $type="_String";
          if($type=="Array" || $type=="_array")
              $type="_Array";
          $cName='\model\reflection\Types\meta\\'.$type;
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
                if(isset($def["MODEL"]))
                {

                    $newType=TypeFactory::getType(null,TypeFactory::getObjectField($def["MODEL"],$def["FIELD"]),$value);
                    //$newType->setTypeReference($def["MODEL"],$def["FIELD"]);
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
            if($type[0]!=='\\') {
                $type=ucfirst(strtolower($type));
                $targetType = TypeFactory::includeType($type);
                $t = new $targetType($def, $value);
                return $t;
            }
            else
            {
                return new $type($def,$value);
            }
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
              if(isset($def["EXTENDS"]))
                      return TypeFactory::getType($def["EXTENDS"],$def,$value);
              else
                  throw($e);
          }
      }


      static function getObjectField($objectName,$fieldName)
      {

          $def=\model\reflection\Types\meta\TypeFactory::getObjectDefinition($objectName);
          if($def["FIELDS"][$fieldName])
          {
              return $def["FIELDS"][$fieldName];
          }
          if($def["ALIASES"][$fieldName])
              return $def["ALIASES"][$fieldName];
          if(strpos($fieldName,"/")!==false)
          {
              $s=\Registry::getService("model");
              $model=$s->getModel($objectName);
              return $model->__getField($fieldName)->getDefinition();
          }
          if($def["EXTENDS"])
              return TypeFactory::getObjectField($def["EXTENDS"],$fieldName);
      }



      static function getAliasField($objectName,$fieldName)
      {
          $def=\model\reflection\Types\meta\TypeFactory::getObjectDefinition($objectName);
          return $def["ALIAS"][$fieldName];
      }

      static function getFieldTypeInstance($objectName,$fieldName)
      {
          $definition=\model\reflection\Types\meta\TypeFactory::getObjectField($objectName,$fieldName);
          return TypeFactory::getType(null,$definition);
      }

      static function getRelationFieldTypeInstance($objectName,$fieldName)
      {
          $type=\model\reflection\Types\meta\TypeFactory::getFieldTypeInstance($objectName,$fieldName);
          return $type->getRelationshipType();
      }

       static function getObjectDefinition($objName,$layer=null)
      {

          if(is_string($objName))
          {
              if(trim($objName)=="")
              {
                  print_r(debug_backtrace());
              }
              $objName=\lib\model\ModelService::getModelDescriptor($objName,$layer);
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
      static function getTypeMeta($mixedType)
      {
          if(is_array($mixedType))
          {
              $mixedType=TypeFactory::getType(null,$mixedType);
          }
           $className=get_class($mixedType);
          $typeList=array_values(class_parents($className));
          $nEls=array_unshift($typeList,$className);

          for($k=0;$k<$nEls;$k++)
          {
              if($typeList[$k]=='model\reflection\Types\meta\BaseType')
                  break;
              $sName=$typeList[$k]."Meta";
              if(@class_exists($sName))
              {
                  $i=new $sName();
                  return $i->getMeta($mixedType);
              }
          }
         $metaClass= new \model\reflection\Types\meta\BaseTypeMeta();
         return $metaClass->getMeta($mixedType);
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
      static function addTypeProvider($path,$provider)
      {
          TypeFactory::$typeProviders[$path]=$provider;
      }
      private static function resolveType($typeName)
      {
          $maxLength=0;
          $provider=null;
          foreach(TypeFactory::$typeProviders as $key=>$value)
          {
              if(strpos($typeName,$key)===0)
              {
                  $l=strlen($key);
                  if($l>$maxLength)
                  {
                      $maxLength=$l;
                      $resolver=$value;
                  }
              }
          }
          return $resolver->getTypeClass($typeName);
      }
  }
?>
