<?php
  namespace model\reflection\Model;
  // Clase helper para la creacion de storage
  
  class FakeModelDefinition
  {
      var $definition;
      var $fields=array();
      function __construct($definition)
      {          
          $this->definition=$definition;

            foreach($this->definition["FIELDS"] as $key=>$value)
            {
                if(\model\reflection\Model\Field::isRelation($value))
                    $this->fields[$key]=new \model\reflection\Model\Relationship\Relationship($key,$this,$value);
                else
                    $this->fields[$key]=new \model\reflection\Model\Field($key,$this,$value);
            }
      }
      function getDefinition()
      {
          return $this->definition;
      }
      function getTableName()
      {
          return $this->definition["TABLE"];
      }
      function getField($name)
      {
          return $this->fields[$name];
      }
      function getFieldOrAlias($name)
      {
          return $this->getField($name);
      }
      function isConcrete()
      {
            return false;
      }
      function getReferencedField($fieldName)
      {
          return $this->definition["REFERENCES"][$fieldName];
      }
      
  }
?>
