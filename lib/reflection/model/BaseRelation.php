<?php
namespace lib\reflection\model;
class BaseRelation extends ModelComponent
{
     var $targetObject;
     var $fields;
     var $localFields;
     var $remoteFields;
     // los campos se van a estandarizar en la forma campo=>relacionado.
     function __construct($name,$parentModel,$definition)
     {
             $definition["MULTIPLICITY"] = io($definition,"MULTIPLICITY","1:N");
             if(!isset($definition["MODEL"]))
             {
                 $p=1;
                 $h=2;
             }
             $this->targetObject = $definition["MODEL"];
             parent::__construct($name,$parentModel,$definition);
             if(isset($this->definition["FIELD"])) {
                                 
                     $this->definition["FIELDS"]=array($name=>$this->definition["FIELD"]);
                     //unset($this->definition["FIELD"]);
             }             
     }

     function isRelation()
     {
          return true;
     }
     function getRole()
     {
         return $this->definition["ROLE"];
     }
     function getMultiplicity()
     {
         return $this->definition["MULTIPLICITY"];
     }
     function isUnique()
     {
         return isset($this->definition["UNIQUE"])?$this->definition["UNIQUE"]:false;
     }
     function isState() 
     {
                 return false;
     }
     
     function getRemoteModelName()
     {
         //return $this->parentModel->objectName->getNormalizedName();
             return $this->definition["MODEL"];
     }
     function getRemoteModel()
     {
            $tModel=\lib\reflection\ReflectorFactory::getModel($this->definition["MODEL"]);
            if($tModel==null)
            {
                var_dump($this->definition["MODEL"]);
            }
            return $tModel;
     }

     function getRemoteObjectName()
     {
         $model=$this->getRemoteModel();
         return $model->objectName->getNormalizedName();
     }
     function getRemoteFieldNames()
     {
             return array_values($this->definition["FIELDS"]);
     }
     function getRemoteFieldInstances()
     {
         $instance= \lib\reflection\ReflectorFactory::getModel($this->definition["MODEL"]);
         return $instance->getFieldOrAlias($this->definition["FIELDS"][$this->getName()]);
     }
    function getRelated($path)
    {
        $instance=$this->getRemoteModel();
        return $instance->getField($path);
    }
     function getLocalFieldNames()
     {
             return array_keys($this->definition["FIELDS"]);
     }
     function getRelationFields()
     {
             return $this->definition["FIELDS"];
     }
     
     function isLocalMultiple() {
         $mult = $this->definition["MULTIPLICITY"];
         if ($mult == "M:N")
             return true;
         return false;
     }

     function isRemoteMultiple() {
         $mult = $this->definition["MULTIPLICITY"];
         if ($mult == "1:N" || $mult == "M:N")
             return true;
         return false;
     }

     function getRelationTypes() {
         $targetObj=new ObjectDefinition($this->getTargetObject());
         foreach($this->definition["FIELDS"] as $key=>$value)
             $types[$key]=\lib\model\types\TypeFactory::getRelationFieldTypeInstance($targetObj->className, $value);
         return $types;
     }

     function pointsTo($modelName,$fieldName)
      {          

          $remObj=new \lib\reflection\model\ObjectDefinition($this->definition["MODEL"]);
          
          if($modelName!=$remObj->getNormalizedName())
              return false;
          if(!is_array($fieldName))
              $fieldName=array($fieldName);
          
          $remoteNames=array_keys($this->getRemoteMapping());
          $int=\lib\php\ArrayTools::compare($remoteNames,$fieldName);
          return $int===0;          
      }
      function getRawDefinition()
      {
          return $this->definition;
      }
  
}
?>
