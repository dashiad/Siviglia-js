<?php
namespace model\reflection\Model\Type;
class TypeReflectionFactory
{
        static function getReflectionType($typeDef)
        {
           if(!isset($typeDef["TYPE"]) && ((isset($typeDef["MODEL"]) && isset($typeDef["FIELD"])) || isset($typeDef["REFERENCES"])))
                   return new ModelReferenceType($typeDef);
              $type=$typeDef["TYPE"]."Type";
              
              $cName='\model\reflection\Model\Type\\'.$type;
              
              if(is_file(PROJECTPATH."/model/reflection/Model/objects/Type/".$type.".php"))
              {
                  include_once(PROJECTPATH."/model/reflection/Model/objects/Type/".$type.".php");
              }
              else
              {              
                  $cName='\app\model\types\reflection\\'.$type;
                  if(!class_exists($cName))
                  {                      
                      return new BaseType($typeDef);
                  }          
              }
              return new $cName($typeDef);
        }
}
