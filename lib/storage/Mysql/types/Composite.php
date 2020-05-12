<?php namespace lib\storage\Mysql\types;


class Composite
{
    function serialize($name,$type,$serializer,$model=null)
    {
        $subTypes=$type->getSubTypes();
        $results=array();
        foreach($subTypes as $key=>$value)
        {

            $res=$serializer->serializeType($key,$value,$serializer);
            foreach($res as $k=>$v)
                $results[$name."_".$k]=$v;
        }
        return $results;
    }
    function unserialize($name,$type,$value,$serializer,$model=null)
    {
        $subTypes=$type->getSubTypes();
        foreach($subTypes as $keyT=>$valueT)
        {
            $serializer->unserializeType($name."_".$keyT,$valueT,$value,$model);
        }
    }

    function getSQLDefinition($name,$definition,$serializer)
    {
        $type=\lib\model\types\TypeFactory::getType($name,$definition,null);

        $definition=$type->getDefinition();
        $results=array();
        $finalResults=[];
        foreach($definition["FIELDS"] as $key=>$value)
        {
            $typeInstance=\lib\model\types\TypeFactory::getType($key,$value,null);
            $subSerializer=$serializer->getTypeSerializer($typeInstance);
            $subDefinitions=$subSerializer->getSQLDefinition($key,$value,$serializer);
            if(!\lib\php\ArrayTools::isAssociative($subDefinitions))
                $results=array_merge($results,$subDefinitions);
            else
                $results[]=$subDefinitions;
        }
        foreach($results as $key=>$value)
        {
            $finalResults[]=array("NAME"=>$name."_".$value["NAME"],"TYPE"=>$value["TYPE"]);
        }
        return $finalResults;
    }
}
