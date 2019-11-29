<?php namespace lib\model\types;
define("TYPE_SET_ON_SAVE",0x1);
define("TYPE_SET_ON_ACCESS",0x2);

class Composite extends BaseType
{
      var $__types;
      var $__definition;

      var $__fields;
      var $__dirty=false;
      function __construct($definition,$subTypeDef,$values=array())
      {

         $this->__fields=$definition["FIELDS"];
         foreach($this->__fields as $key=>$value)
             $this->__types[$key]=\lib\model\types\TypeFactory::getType(null,$value,isset($values[$key])?$values[$key]:null);
         $definition["FIELDS"]=$subTypeDef["FIELDS"];
         BaseType::__construct($definition,false);
      }
      function getFields()
      {
          return array_keys($this->__fields);
      }

      function __set($fieldName,$value)
      {
          if(!$this->__types[$fieldName])
              throw new BaseTypeException(BaseModelException::ERR_NOT_A_FIELD,array("field"=>$fieldName));
          if($this->__types[$fieldName]->equals($value))
              return;
          $this->__dirty=true;
          $this->valueSet=true;
          $this->__types[$fieldName]->setValue($value);
      }
      function __get($fieldName)
      {
          return $this->__types[$fieldName]->get();
      }
      function get()
      {
          return $this;
      }
      // Si él mismo tiene valor, o alguno de sus hijoz tiene valor, el resultado es true.
      function hasOwnValue()
      {
          if($this->valueSet==true)
              return true;
          foreach($this->__types as $key=>$value)
          {
              if($value->hasOwnValue())
                return true;
          }
          return false;
      }

      function setValue($arr)
      {
          if(!$arr)
              return;

          $this->__dirty=true;
          foreach($this->__types as $key=>$value)
          {
              if($arr[$key])
              {
                  $value->setValue($arr[$key]);
              }
          }
          $newVals=array();
          foreach($this->__types as $key=>$value)
          {
              $prefix=$key."_";
              $prefixlen=strlen($prefix);
              $foundVals=null;
              foreach($arr as $valKey=>$valValue)
              {
                  $pos=strpos($valKey,$prefix);
                  if($pos!==false)
                  {
                      $foundVals[substr($valKey,$prefixlen)]=$valValue;
                  }
              }
              if($foundVals)
              {
                  $value->setValue($foundVals);
              }
          }
          $this->valueSet=true;
      }
      function validate($arr)
      {
          foreach($this->__types as $key=>$value)
          {
              if($arr[$key])
                  $value->validate($arr[$key]);
              else
              {
                  if(io($this->definition,"REQUIRED",false))
                      throw new BaseTypeException(BaseTypeException::ERR_INCOMPLETE_TYPE,array("req"=>$key));
              }
          }
      }
      function equals($values)
      {
          if(is_a($values,'\lib\model\types\Composite'))
          {
              foreach($this->__types as $key=>$value)
              {
                  if(!$value->equals($values->{$key}))
                  {
                      return false;
                  }
              }
              return true;
          }
          foreach($this->__types as $key=>$value)
          {
              if(!$value->equals($values[$key]))
              {
                  return false;
              }
          }
          return true;
      }

      function hasValue()
      {
          foreach($this->__types as $key=>$value)
          {
              if(!$value->hasValue() AND $this->definition[$key]["REQUIRED"])
                  return false;
          }
          return true;

      }
      function is_set()
      {
          foreach($this->__types as $key=>$value)
          {
              if(!$value->is_set() AND $this->definition[$key]["REQUIRED"])
                  return false;
          }
          return true;
      }

      function isDirty()
      {
           foreach($this->__types as $key=>$value)
          {
              if(!$value->is_set() AND $this->definition[$key]["REQUIRED"])
                  return false;
          }
          return false;
      }
      function clean()
      {
          $this->__dirty=false;
      }
      function getValue()
      {
          foreach($this->__types as $key=>$value)
          {

              $fDef=$this->__fields[$key];
              if(!$value->hasValue())
              {

                  if($fDef["REQUIRED"])
                  {
                        $flags=$value->flags;
                        if(!($flags & TYPE_SET_ON_SAVE))
                        {
                            if($flags & TYPE_SET_ON_ACCESS)
                            {
                                $results[$key]=$value->getValue();
                            }
                            else
                                throw new \lib\model\BaseTypeException(\lib\model\BaseTypeException::INCOMPLETE_TYPE,array("field"=>$key));
                        }
                  }
              }
              else
              {
                  $results[$key]=$value->getValue();
              }
          }
          return $results;
      }

      function getSubTypes()
      {
          return $this->__types;
      }

}

class CompositeMYSQLSerializer
{
    function serialize($type,$serializer)
    {
        $subTypes=$type->getSubTypes();
        $results=array();
        foreach($subTypes as $key=>$value)
        {

            $val=\lib\model\types\TypeFactory::serializeType($value,"MYSQL");

            if(is_array($val))
            {
                foreach($val as $key2=>$val2)
                    $results[$key."_".$key2]=$val2;
            }
            else
                $results[$key]=$val;
        }
        return $results;
    }

    function getSQLDefinition($name,$definition,$serializer)
    {
        $type=\lib\model\types\TypeFactory::getType(null,$def);

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

class CompositeCASSSerializer
{

    function serialize($type,$serializer)
    {
        $subTypes=$type->getSubTypes();
        $results=array();
        foreach($subTypes as $key=>$value)
        {

            $val=\lib\model\types\TypeFactory::serializeType($value,"MYSQL");

            if(is_array($val))
            {
                foreach($val as $key2=>$val2)
                    $results[$key."_".$key2]=$val2;
            }
            else
                $results[$key]=$val;
        }
        return $results;
    }

    function getCASSDefinition($name,$def)
    {
        $type=\lib\model\types\TypeFactory::getType(null,$def);

        $definition=$type->getDefinition();
        $results=array();

        foreach($definition["FIELDS"] as $key=>$value)
        {
            $type=$value["TYPE"];
            $subSerializer=\lib\model\types\TypeFactory::getSerializer($type,"CASS");
            $subDefinitions=$subSerializer->getCASSDefinition($key,$value);
            if(!CArrayTools::isAssociative($subDefinitions))
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
