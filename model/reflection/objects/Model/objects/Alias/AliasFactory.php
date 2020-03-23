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
                        $typeReflectionFile=__DIR__."/../InverseRelation/InverseRelation.php";
                        $className='\model\reflection\Model\InverseRelation';
                    }break;
                case "RelationMxN":
                    {
                        $typeReflectionFile=__DIR__."/../MultipleRelationship/MultipleRelationship.php";
                        $className='\model\reflection\Model\MultipleRelationship';
                    }break;
                case "TreeAlias":
                    {
                        $typeReflectionFile=__DIR__."/../TreeAlias/TreeAlias.php";
                        $className='\model\reflection\Model\TreeAlias';
                    }break;
                }

                include_once($typeReflectionFile);
                 // Los "alias" siempre tienen un parentModel.Los "types", no necesariamente.
                 // Por eso, los constructores de alias tienen $parentModel como primer parametro del constructor.
                return new $className($name,$parentModel,$definition);
        }
}
