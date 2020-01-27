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
                $subTypes[$flist[$k]]=\lib\model\types\TypeFactory::getRelationFieldTypeInstance($obj,$flist[$k]);
              }

          if(count($subTypes)>1)
              return $subTypes;
          return $subTypes[$flist[0]];
      }
      function _setValue($val)
      {
          $this->value=$val;
          $this->valueSet=true;
      }
      function _validate($val)
      {
          $s=\Registry::getService("model");
          $m=$s->getModel($this->definition["MODEL"]);
          $parentModel=$this->parent;
          foreach($this->definition["FIELDS"] as $key=>$value)
          {
              $m->{$value}=$val;
          }
          try {
              $m->loadFromFields();
          }catch(\Exception $e)
          {
              throw new BaseTypeException(BaseTypeException::ERR_INVALID,["val"=>$val],$this);
          }
          return true;
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
          return $this->value==$v->value;
      }
    function getMetaClassName()
    {
        include_once(PROJECTPATH."/model/reflection/objects/Types/meta/Relationship.php");
        return '\model\reflection\Types\meta\Relationship';
    }
}

