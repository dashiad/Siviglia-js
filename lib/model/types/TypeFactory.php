<?php namespace lib\model\types;
use model\reflection\Model\Type;

include_once(LIBPATH."/model/types/BaseType.php");
  class TypeFactory
  {
      static $typeProviders=array();
      static $typeProviderCache=array();
      static $installedTypes=[];

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

          //$type=ucfirst(strtolower($type));
          if($type=="String" || $type=="_string")
              $type="_String";
          if($type=="Array" || $type=="_array")
              $type="_Array";
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
                  throw new BaseTypeException(BaseTypeException::ERR_TYPE_NOT_FOUND,array("name"=>$type),null);
             // }
          }
          return $cName;
      }
      // OJO, que este tipo va a ser global...Si 2 bto instalan 2 tipos con el mismo nombre, se van a pisar!
      // Esto se resolveria si al instanciar un tipo, se le pasara el bto padre, para que las posibles
      // definiciones de tipos extendidos, se buscaran solo en el bto padre.En ese caso, ni siquiera haria
      // falta una variable estatica que almacenara los tipos, ni que los bto tuvieran que instalarlos.
      static function installType($name,$def)
      {
            TypeFactory::$installedTypes[$name]=$def;
      }

      static function getType($name,$def,$parentType,$value=null,$validationMode=\lib\model\types\BaseType::VALIDATION_MODE_COMPLETE)
      {

              if(is_string($def))
              {
                  $replaced=str_replace('\\','/',$def);
                  if(!strchr($replaced,'/'))
                  {
                      // Si es una cadena, y no pertenece a ninguna clase, es que es de reflection.
                      $def='\model\reflection\Types\types\\'.$def;
                  }
                  // Se mira si el tipo ha sido instalado previamente por algun BaseTypedObject
                  if(isset(TypeFactory::$installedTypes[$def]))
                  {
                      return TypeFactory::getType($name,TypeFactory::$installedTypes[$def],$parentType,$value,$validationMode);
                  }

                  $info = \lib\model\Package::getInfoFromClass($def);
                  if($info==null)
                      throw new BaseTypeException(BaseTypeException::ERR_TYPE_NOT_FOUND,array("def"=>$def),null);
                  if($info["resource"]!==\lib\model\Package::TYPE)
                      throw new BaseTypeException(BaseTypeException::ERR_TYPE_NOT_FOUND,array("def"=>$def),null);
                  include_once($info["file"]);
                  $class=$info["class"];
                  return new $class($name,$parentType,$value,$validationMode);
              }

            if(!isset($def["TYPE"]))
            {
                if(isset($def["MODEL"]))
                {

                    $newType=TypeFactory::getType($name,TypeFactory::getObjectField($def["MODEL"],$def["FIELD"]),$parentType,$value,$validationMode);
                    //$newType->setTypeReference($def["MODEL"],$def["FIELD"]);
                    // En caso de que la referencia defina un DEFAULT, sobreescribe al que llega de la definicion de tipo.
                    if(isset($def["DEFAULT"]) && !empty($def["DEFAULT"]))
                    {
                        $newType->setDefaultValue($def["DEFAULT"]);
                    }
                    return $newType;
                }
                throw new \lib\model\types\BaseTypeException(\lib\model\types\BaseTypeException::ERR_TYPE_NOT_FOUND,["type"=>$def["TYPE"]]);
            }

            $type=str_replace('/','\\',$def["TYPE"]);

            if($type[0]!=='\\') {
                //$type=ucfirst(strtolower($type));
                $targetType = TypeFactory::includeType($type);
                return new $targetType($name,$def,$parentType, $value,$validationMode);
            }
            else
            {
                // Los tipos "custom" son de tipo:
                // \model\site\types\typeName
                // Este codigo quita las dos ultimas partes, y busca el modelDescriptor para ese tipo.

                $info = \lib\model\Package::getInfoFromClass($type);
                if($info)
                    {
                        include_once($info["file"]);
                        return new $info["class"]($name,$parentType,$value,$validationMode);
                            throw new BaseTypeException(BaseTypeException::ERR_TYPE_NOT_FOUND,array("name"=>$type),null);
                        return $instance;
                    }

                throw new BaseTypeException(BaseTypeException::ERR_TYPE_NOT_FOUND,array("name"=>$type),null);
            }

      }
/*


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
 */

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
              $s=\Registry::getService("model");
              $model=$s->getModel($objectName);
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

      static function getFieldTypeInstance($objectName,$fieldName,$model,$value=null,$validationMode=\lib\model\types\BaseType::VALIDATION_MODE_COMPLETE)
      {
          $definition=\lib\model\types\TypeFactory::getObjectField($objectName,$fieldName);
          return TypeFactory::getType($fieldName,$definition,$model,null,$model->getValidationMode());
      }

      static function getRelationFieldTypeInstance($objectName,$fieldName,$model,$value=null,$validationMode=\lib\model\types\BaseType::VALIDATION_MODE_COMPLETE)
      {
          $type=\lib\model\types\TypeFactory::getFieldTypeInstance($objectName,$fieldName,$model,$value,null);
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
          if($objName==null)
              $s=11;
          //$objName->getDefinition();
          $className=$objName->getNamespaced();//.'\Definition';
          if(!class_exists($className))
          {
              throw new BaseTypeException(BaseTypeException::ERR_TYPE_NOT_FOUND,array("name"=>$className),null);
          }
          // Se instancia, por si hay que hacer inicializacion de la definicion en el constructor.
          $n=new $className();
          return $n->getDefinition();
       }
      static function getTypeMeta($mixedType)
      {
          if(is_array($mixedType))
          {
              $mixedType=TypeFactory::getType(null,$mixedType,null);
          }
           $className=get_class($mixedType);
          $typeList=array_values(class_parents($className));
          $nEls=array_unshift($typeList,$className);

          for($k=0;$k<$nEls;$k++)
          {
              if($typeList[$k]=='lib\model\types\BaseType')
                  break;
              $sName=$typeList[$k]."Meta";
              if(@class_exists($sName))
              {
                  $i=new $sName();
                  return $i->getMeta($mixedType);
              }
          }
         $metaClass= new \lib\model\types\BaseTypeMeta();
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
