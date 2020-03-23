<?php
namespace model\reflection\Model;
class TypeReflectionFactory
{
        static function getReflectionType($typeDef)
        {
           if(!isset($typeDef["TYPE"]) && ((isset($typeDef["MODEL"]) && isset($typeDef["FIELD"])) || isset($typeDef["REFERENCES"])))
                   return new ModelReferenceType($typeDef);
              $type=$typeDef["TYPE"];
              if($type=="String" || $type=="Array")
                  $type="_".$type;

              $cName='\model\reflection\Types\types\meta\\'.$type;

              if(is_file(PROJECTPATH."/model/reflection/objects/Types/types/".$type.".php"))
              {
                  include_once(PROJECTPATH."/model/reflection/objects/Types/types/".$type.".php");
              }
              else
              {
                  $cName='\model\reflection\Types\types\meta\\'.$type;
                  if(!class_exists($cName))
                  {
                      include_once(PROJECTPATH."/model/relfection/objects/Types/types/BaseType.php");
                      return new \model\reflection\Types\types\meta\BaseType($typeDef);
                  }
              }
              return new $cName($typeDef);
        }
}
