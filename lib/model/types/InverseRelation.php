<?php
 namespace lib\model\types;
 class InverseRelation extends Relationship
 {
     var $mustBeSetToDirty=false;
        function __construct($name,$definition, $parentType, $value=null,$validationMode=null)
        {
                $targetObject=$definition["MODEL"];
                if($definition["FIELD"])
                {
                    $src=array("x"=>$definition["FIELD"]);
                }
                else
                {
                    if($definition["FIELDS"])
                    $src=$definition["FIELDS"];                    
                      
                }
                $newFields=array();

                foreach($src as $key1=>$value1)
                {
                    $def=\lib\model\types\TypeFactory::getObjectField($targetObject,$value1);

                    foreach($def["FIELDS"] as $keyR=>$valueR)
                    {
                        $newFields[$valueR]=$keyR;
                        if($parentType->{"*".$valueR}->hasOwnValue())
                            $cValue=$parentType->{$valueR};
                    }
                }

                $definition["FIELDS"]=$newFields;
                $this->isAlias=true;
                parent::__construct($name,$definition, $parentType, $cValue,$validationMode);

        }
     function _validate($val)
     {
        if(is_array($val))
        {
            $service=\Registry::getService("model");
            $instance=$service->getModel($this->definition["MODEL"]);
            for($k=0;$k<count($val);$k++)
            {
                $instance->validate($val[$k]);
            }
        }
     }
     function checkSource($value)
     {
         return true;
     }
     function createRelationFields()
     {
         return new InverseRelationFields($this,$this->definition);
     }
     function isAlias()
     {
         return true;
     }

     function clear()
     {
         // LLamado en caso de que $value sea nulo.
         $this->relationValues->load([],true);
     }

     function isInverseRelation()
     {
         return true;
     }
     function setValue($val,$validationMode=null){
         if($val===[])
             $val=null;
         $this->mustBeSetToDirty=false;
         parent::apply($val,\lib\model\types\BaseType::VALIDATION_MODE_NONE);
         if($this->mustBeSetToDirty)
             $this->setDirty(true);
     }
     function set($value,$validationMode=null)
     {
         $this->relationValues->reset();
         // Una relacion inversa debe ponerse a dirty cuando lo que se establece es el valor (rows remotas)
         // Nunca cuando se establece el campo de relacion, porque una relacion inversa no tiene campo,
         // es un alias.
         // Asi que, nunca hqy que validarla..Pero eso hace que en baseType se llame a _setValue con
         // VALIDATION_NONE, lo que hace que , aunque se añada como dirty, apply lo va a borrar.
         // Asi que hay que volverlo a introducir a la salida de apply, o sea, sen setValue, que
         // es donde recuperamnos $mustBeSetToDirty, y lo volvemos a introducir como sucio.
         if(is_object($value))
         {
             if (is_subclass_of($value, "\\lib\\model\\BaseModel"))
             {
                 $this->relationValues->load(array($value), true);
                 $this->mustBeSetToDirty=true;
             }
             // TODO : Que se nos ha pasado entonces??
             return;
         }
         if(is_array($value))
         {
             $this->relationValues->load($value, true);
             $this->mustBeSetToDirty=true;
             return;
         }
         $this->relation->set($value);
         $this->valueSet=true;

     }
     function requiresUpdateOnNew()
     {
         // Para las relaciones "normales", es decir , A tiene una relacion con B, y estoy guardando A, siempre hay
         // que guardar primero B, obtener su valor, y copiarlo en A.
         // No es posible primero guardar A y luego hacer update de B.
         // Sin embargo, en las relaciones inversas y multiples, si que es necesario primero guardar A, y luego hacer update en B.
         // En la clase de relacion inversa, este metodo se sobreescribe, devolviendo siempre true.
         return true;
     }
     // TODO: Aqui hay un problema, que habria que resolver limpiamente:
     // Cuando se llega a esta llamada, se ha intentado guardar la relacion previamente.Pero
     // no fue posible, ya que el modelo que contiene esta relacion inversa, era nuevo, por
     // lo que no tenia id que mapear.
     // Asi que cuando llegamos aqui, por algun motivo, se ha limpiado ya el estado de
     // la relacion inversa. En relationValues, si que estan los objetos remotos a los que hay
     // que mapear el id, pero 1) Llamar a count() de relationValues, va a ejecutar una query, no
     // va a tener en cuenta lo que hay en sus relatedObjects, y 2) Los indices accedidos estan limpios.
     // Esta funcion está usando una puerta trasera (getRelatedObjects, markAsAccessed), para conseguir guardar
     // esos objetos pendientes. Lo que hay que hacer, es que el estado no se resetee, que un count() no intente
     // hacer queries, y que los accessedIndexes no desaparezcan antes de haber llamado a onModelSaved
     function onModelSaved()
     {
         if(!$this->relation->is_set() && $this->getModel()->__isNew())
         {
             // Tenemos los objetos A y B. B tiene una relacion con A, asi que A tiene una relacion inversa con B, y esta relacion es un alias, y esta clase es ese alias.
             // Aqui estamos en caso de que se ha creado un A, y, a traves de el, uno o varios B.Ahora se ha guardado A, asi que tenemos que copiar el campo relacion, de A, a todos
             // los B que se hayan creado.
             $relValues=$this->relationValues->getRelatedObjects();
             $this->relation->setFromModel($this->__controller);

             for($k=0;$k<count($relValues);$k++)
             {
                 $cObject=$relValues[$k];
                 $this->relation->setToModel($cObject);
                 $this->relationValues->markAsAccessed($k);
             }
             $this->relationValues->save();
             $this->relation->save();
             $this->setDirty(false);

         }


     }
     function getRemoteField()
     {
         $vals=array_values($this->definition["FIELDS"]);
        return $vals[0];
     }
     function save()
     {
         if($this->__controller->__isNew())
         {
             $this->__controller->__addPostSaveField($this);
             return;
         }
         parent::save();
     }
 }

 class InverseRelationFields extends \lib\model\types\base\RelationFields
 {
     protected $remoteInstance;
     function __construct(&$relObject, $definition)
     {
         $this->relObject = $relObject;
         $this->definition = $definition;
         $fields = $definition["FIELDS"] ? $definition["FIELDS"] : (array)$definition["FIELD"];
         if (!\lib\php\ArrayTools::isAssociative($fields)) {
             $fields = array($this->relObject->getName() => $fields[0]);
         }
         $modelClassName = $relObject->getRemoteObject();
         $this->remoteInstance=$relObject->getRemoteModel();
         foreach ($fields as $key => $value) {
             $this->fieldKey = $key;
             $this->nFields++;

             $this->types[$key] = \lib\model\types\TypeFactory::getRelationFieldTypeInstance(
                 $modelClassName,
                 $value,
                 $this->relObject->__getName(),
                 $this->remoteInstance,
                 null,
                 $relObject->getValidationMode());

             if (isset($definition["DEFAULT"])) {
                 $this->types[$key]->apply($definition["DEFAULT"]);
             }

         }
         $this->definition["FIELDS"] = $fields;
         $this->state = \lib\model\types\base\ModelBaseRelation::UN_SET;
     }
     function getRemoteInstance($value)
     {

     }
 }
?>
