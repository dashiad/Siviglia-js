<?php namespace lib\model\types;
use \lib\model\types\base\ModelBaseRelation;
use \lib\model\types\base\RelationValues;
use \lib\model\types\base\RelationFields;
// El tipo Relacion solo existe para poder "redireccionar" columnas de tipo Relationship, a su tipo padre.
class Relationship extends \lib\model\types\base\ModelBaseRelation {
    function getRemoteFields()
    {
        $f=$this->definition["FIELDS"];
        return array_values($f);
    }
    function getLocalFields()
    {
        $f=$this->definition["FIELDS"];
        return array_keys($f);
    }
    function createRelationValues()
    {
        return new RelationValues($this, isset($this->definition["LOAD"]) ? $this->definition["LOAD"] : "LAZY");
    }
    function getRelationValues()
    {
        return $this->relationValues;
    }
    function hasOwnValue()
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
    function clear()
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

        if (isset($this->definition["LOAD"]) && $this->definition["LOAD"] == "LAZY")
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


    function getRelationshipType($name,$parent)
      {
          $obj=$this->definition["MODEL"];

          $fields=$this->definition["FIELDS"];
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
          return $s->getModel($this->definition["MODEL"]);

      }
      function _validate($val)
      {
          $s=$this->getSource();
          return $s->contains($val);
      }
      function hasSource()
      {
          return true;
      }
      function getSource($validating=false)
      {
          $keys=array_keys($this->definition["FIELDS"]);
          $metadata=isset($this->definition["SOURCE"])?$this->definition["SOURCE"]:null;
          if($metadata!==null && isset($metadata["LABEL"])) {
              $label = $metadata["LABEL"];
          }
          else {
              $model = $this->getRemoteModel();
              $descriptive=$model->__filterFields("DESCRIPTIVE",true);
              $label="[%".$descriptive[0]."%]";
          }
          $param=["TYPE"=>"DataSource",
              "MODEL"=>$this->definition["MODEL"],
              "DATASOURCE"=>isset($metadata["DATASOURCE"])?$metadata["DATASOURCE"]:"FullList",
              "VALUE"=>$this->definition["FIELDS"][$keys[0]],
              "LABEL"=>$label
          ];
          if(isset($this->definition["PARAMS"]))
              $param["PARAMS"]=$this->definition["PARAMS"];
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
    function getMetaClassName()
    {
        include_once(PROJECTPATH."/model/reflection/objects/Types/Relationship.php");
        return '\model\reflection\Types\meta\Relationship';
    }
    function onModelSaved()
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

    function copyField($type)
    {
        $this->relation->copyField($type);

    }
}

