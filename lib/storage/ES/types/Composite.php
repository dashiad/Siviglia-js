<?php namespace lib\storage\ES\types;


class Composite
{
    function serialize($name,$type,$serializer)
    {
        $subTypes=$type->getSubTypes();
        $results=array();
        foreach($subTypes as $key=>$value)
        {
            $val=$serializer->serializeType($key,$value);
            foreach($val as $key2=>$val2)
                $results[$name."_".$key2]=$val2;
        }
        return $results;
    }
    function unserialize($name,$type,$value,$serializer)
    {
        $subTypes=$type->getSubTypes();
        foreach($subTypes as $keyT=>$valueT)
        {
            $serializer->unserializeType($name."_".$keyT,$valueT,$value);
        }
    }

    function getSQLDefinition($name,$definition,$serializer)
    {
        $type=\lib\model\types\TypeFactory::getType(null,$definition);

        $definition=$type->getDefinition();
        $results=array();

        foreach($definition["FIELDS"] as $key=>$value)
        {
            $type=\lib\model\types\TypeFactory::getType(null,$value);
            $typeSerializer=$serializer->getTypeSerializer($type);
            $subDefinitions=$typeSerializer->getSQLDefinition($key,$value,$serializer);

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
