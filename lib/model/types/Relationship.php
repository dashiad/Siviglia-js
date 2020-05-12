<?php namespace lib\model\types;

// El tipo Relacion solo existe para poder "redireccionar" columnas de tipo Relationship, a su tipo padre.
class Relationship extends BaseType {
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
    function getRelationshipType()
      {
          $obj=$this->definition["MODEL"];

          $fields=$this->definition["FIELDS"];
          $flist=array_values($fields);
          $subTypes=array();
          for($k=0;$k<count($flist);$k++)
              {
                $subTypes[$flist[$k]]=\lib\model\types\TypeFactory::getRelationFieldTypeInstance($obj,$flist[$k],$this->parent);
              }

          if(count($subTypes)>1)
              return $subTypes;
          return $subTypes[$flist[0]];
      }
      function _setValue($val,$validationMode=null)
      {
          $this->value=$val;
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
}

