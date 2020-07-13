<?php namespace lib\model\types;
use \lib\model\types\base\ModelBaseRelation;
use \lib\model\types\base\RelationValues;
use \lib\model\types\base\RelationFields;
// El tipo Relacion solo existe para poder "redireccionar" columnas de tipo Relationship, a su tipo padre.
class Relationship extends \lib\model\types\base\ModelBaseRelation {
    function getRemoteFields()
    {
        $f=$this->__definition["FIELDS"];
        return array_values($f);
    }
    function getLocalFields()
    {
        $f=$this->__definition["FIELDS"];
        return array_keys($f);
    }
    function createRelationValues()
    {
        return new RelationValues($this, isset($this->__definition["LOAD"]) ? $this->__definition["LOAD"] : "LAZY");
    }
    function getRelationValues()
    {
        return $this->relationValues;
    }
    function __hasOwnValue()
    {
        return $this->relation->is_set();
    }
    function set($value,$validationMode=null)
    {
        $this->relation->set($value);
        $this->relationValues->reset();

        if(is_object($value))
        {
            if (is_subclass_of($value, "\\lib\\model\\BaseModel"))
            {
                $this->relationValues->load(array($value), true);
            }
        }
        if(is_array($value))
        {
            if(\lib\php\ArrayTools::isAssociative($value))
                $this->relationValues->load([$value], true);
            else
                $this->relationValues->load($value, true);
        }
        $this->valueSet=true;
    }
    function __clear()
    {
        // LLamado en caso de que $value sea nulo.
        $this->relation->set(null);
        $this->relationValues->load([],true);
    }
    function get()
    {
        return $this;
    }

    function __get($varName)
    {
        if(is_numeric($varName))
            return $this->relationValues[$varName];
        return $this->relationValues[0]->{$varName};
    }

    function __set($varName, $value)
    {
        return $this->relationValues[0]->{$varName} = $value;
    }
    function loadCount()
    {

        if ($this->relation->state == ModelBaseRelation::UN_SET)
            return 0;

        if (isset($this->__definition["LOAD"]) && $this->__definition["LOAD"] == "LAZY")
            $this->relationValues->setCount($this->getSerializer()->count($this->getRelationQueryConditions(), $this->model));

        else
            return $this->loadRemote(null);
    }

    function save()
    {
        $nSaved = $this->relationValues->save();
        $this->relation->save();
        if ($nSaved == 1)
        {
            $this->relation->setFromModel($this->relationValues[0]);
            $this->relation->save();
        }
        $this->__setDirty(false);
    }

    function count()
    {
        return $this->relationValues->count();
    }


    function __getRelationshipType($name,$parent)
      {
          $obj=$this->__definition["MODEL"];

          $fields=$this->__definition["FIELDS"];
          $subTypes=array();
          // TODO: Aun no se soportan, pero si la relacion tuviera mas de un campo, $name tendria que ser un diccionario con todos los nombres necesarios.
          foreach($fields as $k=>$v)
              $subTypes[]=\lib\model\types\TypeFactory::getRelationFieldTypeInstance($obj,$v,$k,$parent);

          return $subTypes[0];
      }
      function _setValue($val,$validationMode=null)
      {
          $this->value=$val;
          $this->set($val,$validationMode);
          $this->valueSet=true;
      }
      function getRemoteModel()
      {
          $s=\Registry::getService("model");
          return $s->getModel($this->__definition["MODEL"]);

      }
      function _validate($val)
      {
          $s=$this->__getSource();
          if(!$s->contains($val))
              throw new \lib\model\BaseTypedException(\lib\model\BaseTypedException::ERR_INVALID_VALUE,array("value"=>$val));
          return true; // El source se valida aparte.
      }
      function hasSource()
      {
          return true;
      }
      function __getSource($validating=false)
      {
          $keys=array_keys($this->__definition["FIELDS"]);
          $metadata=isset($this->__definition["SOURCE"])?$this->__definition["SOURCE"]:null;
          if($metadata!==null && isset($metadata["LABEL"])) {
              $label = $metadata["LABEL"];
          }
          else {
              $model = $this->getRemoteModel();
              $descriptive=$model->__filterFields("DESCRIPTIVE",true);
              $label="[%".$descriptive[0]."%]";
          }
          $param=["TYPE"=>"DataSource",
              "MODEL"=>$this->__definition["MODEL"],
              "DATASOURCE"=>isset($metadata["DATASOURCE"])?$metadata["DATASOURCE"]:"FullList",
              "VALUE"=>$this->__definition["FIELDS"][$keys[0]],
              "LABEL"=>$label
          ];
          if(isset($this->__definition["PARAMS"]))
              $param["PARAMS"]=$this->__definition["PARAMS"];
          return \lib\model\types\sources\SourceFactory::getSource($this,$param, false);
      }
      function _getValue()
      {
          return $this->value;
      }
      function _copy($val)
      {
          $this->value=$val->value;
      }
      function _equals($v)
      {
          return $this->value==$v;
      }
    function __onModelSaved()
    {
        $this->relation->cleanState();
    }

    function isDirty()
    {
        if ($this->relation->isDirty())
        {
            return true;
        }

        if ($this->relationValues->isLoaded())
        {
            return $this->relationValues->isDirty();
        }
        return false;
    }

    function serialize($serializer)
    {
        return $this->relation->serialize($serializer);
    }


}

