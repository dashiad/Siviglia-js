<?php
namespace model\reflection\Model\Alias;
class AliasFactory
{
        static function getAlias($parentModel,$name,$definition)
        {
                $typeReflectionFile=null;
                $className=null;
                switch($definition["TYPE"])
                {
                case "InverseRelation":
                    {
                        $typeReflectionFile=__DIR__."/InverseRelation.php";
                        $className='\model\reflection\Model\Alias\\InverseRelation';
                    }break;
                case "RelationMxN":
                    {
                        $typeReflectionFile=__DIR__."/../Relationship/MultipleRelationship.php";
                        $className='\model\reflection\Model\Relationship\MultipleRelationship';
                    }break;
                case "TreeAlias":
                    {
                        $typeReflectionFile=__DIR__."/TreeAlias.php";
                        $className='\model\reflection\Model\Alias\\TreeAlias';
                    }break;
                }

                include_once($typeReflectionFile);
                 // Los "alias" siempre tienen un parentModel.Los "types", no necesariamente.
                 // Por eso, los constructores de alias tienen $parentModel como primer parametro del constructor.
                return new $className($name,$parentModel,$definition);
        }
}
