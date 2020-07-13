<?php namespace lib\model;


use lib\model\types\BaseTypeException;

class BaseTypedException extends BaseException {
    const ERR_REQUIRED_FIELD=1;
    const ERR_NOT_A_FIELD=2;
    const ERR_INVALID_STATE=3;
    const ERR_INVALID_STATE_TRANSITION=4;
    const ERR_INVALID_PATH=5;
    const ERR_DOUBLESTATECHANGE=6;
    const ERR_INVALID_STATE_CALLBACK=7;
    const ERR_CANT_CHANGE_FINAL_STATE=8;
    const ERR_NO_STATE_DEFINITION=9;
    const ERR_CANT_CHANGE_STATE=10;
    const ERR_CANT_CHANGE_STATE_TO=11;
    const ERR_REJECTED_CHANGE_STATE=12;
    const ERR_NOT_EDITABLE_IN_STATE=13;
    const ERR_LOAD_DATA_FAILED=14;
    const ERR_UNKNOWN_STATE=15;
    const ERR_INVALID_VALUE=16;
    const ERR_PENDING_STATE_CHANGE=17;
    const ERR_NO_CONTROLLER=18;
    const ERR_NO_STATE_CONTROLLER=19;
    const ERR_NOT_EDITABLE=20;
    const ERR_CANT_SAVE_ERRORED_FIELD=21;
    const ERR_CANT_SAVE_ERRORED_OBJECT=22;
    const ERR_CANT_COPY_ERRORED_FIELD=23;
}

class BaseTypedObject extends \lib\model\types\Container
{

        protected $__objectDef;
        protected $__loaded=0;
        protected $__serializer=null;
        protected $__newState=null;
        protected $__key=null;
        protected $__oldValidationMode=null;

        protected $__validationMode=\lib\model\types\BaseType::VALIDATION_MODE_COMPLETE;

        function __construct($definition,$validationMode=null)
        {
                $this->__objectDef=$definition;
                if(isset($definition["TYPES"]))
                {
                    foreach($definition["TYPES"] as $key=>$value)
                        \lib\model\types\TypeFactory::installType($key,$value);
                }
                $this->__key=null;

            parent::__construct(null,$definition,null,null,$validationMode);
                // Queremos que un bto, de principio, no sea nulo. Esto nos va a permitir asignar directamente
            // sus campos, sin tener que inicializarlo a []
                $this->value=1;
                if (isset($this->__objectDef["INDEXFIELDS"]))
                    $this->__key = new ModelKey($this, $this->__objectDef);

        }
        function __getKeys()
        {
            return $this->__key;
        }
        function getIndexFields()
        {
            if(!$this->__key)
                return [];
            return $this->__key->getKeyNames();
        }
        function __hasKey()
        {
            return $this->__key!==null;
        }

        // Usado para los aliases de BaseModel
        function & __addField($fieldName,$definition)
        {
            $this->__fields[$fieldName]= \lib\model\types\TypeFactory::getType(["fieldName"=>$fieldName,"path"=>$this->fieldNamePath],$definition,$this,null,$this->validationMode);
            $newField=$this->__getField($fieldName);
            //unset($this->__fields[$fieldName]);
            return $newField;
        }
        function beginUnserialize()
        {
            $this->disableStateChecks();
            $this->__savedValidationMode=$this->__validationMode;
            $this->__setValidationMode(\lib\model\types\BaseType::VALIDATION_MODE_NONE);
        }
        function endUnserialize()
        {
            $this->enableStateChecks();
            $this->__loaded=true;
            $this->__setValidationMode($this->__savedValidationMode);
            $this->cleanDirtyFields();
        }
        function __setSerializer($serializer)
        {
            $this->__serializer=$serializer;
        }

        // Prioritiza el orden en el que hay que aplicar un callback.
        // El orden es: primero, campo propio de estado.
        // Segundo, relaciones, y posibles paths asociados a esas relaciones
        // Finalmente, el resto de campos.


        function getAsDictionary($nonSetAsNull=true)
        {
            $fields=$this->__getFields();
            $result=[];
            if(!$fields)
                return $result;

            foreach($fields as $key=>$value) {
                if(!$value->is_set() && $nonSetAsNull==true)
                    $result[$key]=null;
                else
                    $result[$key]=$value->getValue();
            }
            return $result;
        }

        function isLoaded()
        {
            return $this->__loaded;
        }



        function setFields($fields)
        {
            foreach($fields as $key => $value)
            {

                $this->__set($key,$value);
            }

        }

        function __getRequiredFields($state=null)
        {
            if($this->__stateDef->hasState)
            {
                if($state==null)
                    $state=$this->getState();
                return $this->__stateDef->getRequiredFields($state);
            }
            $required=[];
            foreach($this->__fieldDef as $k=>$v)
            {
                if($this->__getField($k)->__isDefinedAsRequired()==true)
                    $required[]=$k;
            }
            return $required;
        }
    function prioritizeChanges($dataOrObj,$callback)
    {
        if(is_a($dataOrObj,'\lib\model\BaseTypedObject'))
        {
            $data=$dataOrObj->getValue();
        }
        else
            $data=$dataOrObj;
        $localFields=[];
        $relationships=[];
        $relationshipPaths=[];
        $invRelationships=[];

        $indexFields=[];
        $referencedModel=$this;
        $stateField=$referencedModel->getStateField();
        $state=null;
        $indexes=$this->getIndexFields();
        foreach($data as $k=>$v)
        {
            if(in_array($k,$indexes))
            {
                $indexFields[$k]=$v;
            }
            else {
                if (strpos($k, "/") > 0) {
                    $path = explode("/", $k);
                    $relation = array_shift($path);
                    $f = $referencedModel->__getField($relation);
                    if (is_a($f, '\lib\model\types\InverseRelation')) {
                        $index = array_shift($path);
                        if (!is_numeric($index))
                            throw new BaseTypedException(BaseTypedException::ERR_INVALID_PATH, ["path" => $index . "/" . implode("/", $path)]);
                        $subPath = implode("/", $path);
                        $invRelationships[$relation][$index][$subPath] = $v;
                    } else {
                        $subPath = implode("/", $path);
                        $relationshipPaths[$relation][$subPath] = $v;
                    }
                    continue;
                }
                if ($k == $stateField) {
                    $state = $k;
                    continue;
                }
                $f = $referencedModel->__getField($k, true);

                if (!is_a($f, '\lib\model\types\Relationship'))
                    $localFields[$k] = $v;
                else {
                    // La siguiente linea significa:
                    // El campo a validar actual es una relacion inversa, pero no es un path.
                    // Es decir, el valor tiene que ser el asignable a una relacion inversa, es decir, un array
                    // de modelos.
                    // Al asignar directamente el valor, tenemos que lo que queremos es establecer el valor de la relacion,
                    // es decir, borrar los elementos relacionados actuales, y establecer los especificados.
                    // Pero, principalmente, hay que ver que como $v es un array, esta especificacion de relaciones inversas es
                    // compatible con la generada usando paths dentro del nombre de campo.
                    if (is_a($f, '\lib\model\types\InverseRelation'))
                        $invRelationships[$k] = $v;

                    else
                        $relationships[$k] = $v;
                }
            }
        }
        // Primero, los campos indice por si hay que deserializer.
        foreach($indexFields as $key=>$value)
        {
            call_user_func($callback, 0, $key, $value, $referencedModel->__getField($key));
        }
        // Luego, se cambia el estado.
        if($state)
        {
            call_user_func($callback,1,$state,$data[$state],$this->__fieldDef["state"]);
        }
        // Luego, se itera por los campos que sean relaciones, y que estan tanto como campos simples, como paths.
        foreach($relationships as $key=>$value)
        {
            call_user_func($callback, 2, $key, $value, $referencedModel->__getField($key));
        }
        // Luego, todos los campos "finales" que pertenecen a este modelo.
        // Esto es necesario para completar, en su caso, el cambio de estado.
        foreach($localFields as $key=>$value)
        {
            call_user_func($callback,4,$key,$value,$referencedModel->__getField($key));
        }
        // Siguiente, cuando el objeto local ya esta listo, se itera por las relaciones que incluyen paths
        foreach($relationshipPaths as $key=>$value)
        {
            call_user_func($callback,3,$key,$value,$referencedModel->__getField($key));
        }
        // Finalmente, las relaciones inversas
        foreach($invRelationships as $key=>$value)
        {
            call_user_func($callback,5,$key,$value,$referencedModel->__getField($key));
        }
    }
    function _setValue($val, $validationMode=null)
    {
        if($validationMode==null)
            $validationMode=$this->validationMode;
        $this->reset();
        $this->__oldValidationMode=$validationMode;
        $this->loadFromArray($val,$this->validationMode==\lib\model\types\BaseType::VALIDATION_MODE_NONE?true:false,
            false,null,true);
        $this->value=1;
        $this->valueSet=true;
    }
    // TODO: Que BaseTypedObject tenga un loadFromFields no es muy canonico...
    function loadFromFields()
    {

    }

    // Si raw es true, el valor se asigna incluso si la validacion da un error.
    function loadFromArray($data,$raw=false,$unserializing=true,$loadResult=null,$dontSave=false)
    {
        // Cargar de un array se tiene que hacer en varias fases.
        // La primera fase, es detectar paths. Si hay paths, hay que cargar primero
        // los elementos propios, y luego los remotos. Asi, evitamos que las relaciones
        // pierdan sus datos.
        // Esto es porque si A es un objeto nuevo, que se relaciona con B (que ya existe), e
        // intentamos modificar B, primero hay que asignar la relacion en A, y luego modificar B,
        // ya que de otro modo, estariamos creando un nuevo B, y luego lo reasignariamos.
        // Por otro lado, primero hay que asignar el campo estado, si existe, y luego, el resto.
        if($loadResult==null)
            $loadResult=new \lib\model\ModelFieldErrorContainer();
        $data=$this->normalizeToAssociativeArray($data);
      /*  if(!$unserializing)
        {
            $result=$this->__validateArray($data,$loadResult);
            if(!$result->isOk())
            {
                throw new \lib\model\BaseTypedException(\lib\model\BaseTypedException::ERR_LOAD_DATA_FAILED);
            }
        }*/
        if($unserializing)
            $this->beginUnserialize();
        else {
            $requiredFields = [];
            $stateFieldName = $this->getStateField();
            $oldState = null;
            if ($stateFieldName) {
                $oldState = $this->{"*" . $stateFieldName}->getLabel();
                $nextState = $oldState;
            }
            $reqs=$this->__getRequiredFields();
            for ($k = 0; $k < count($reqs); $k++)
                $requiredFields[$reqs[$k]] = 1;
        }

        $this->prioritizeChanges($data,function($fieldType,$fieldName,$fieldValue,$fieldDefinition)
        use ($raw,$unserializing,$loadResult,$dontSave)
        {
            switch($fieldType)
            {
                case 0:{
                    $this->{$fieldName}=$fieldValue;
                    if($this->__key->is_set())
                        $this->loadFromFields();
                }break;
                case 2: // Campo relacion, sin path
                    {
                        try {
                            if (is_array($fieldValue)) {
                                $loadResult->pushPath($this->{"*".$fieldName}->__getFieldPath());
                                $this->{$fieldName}[0]->loadFromArray($fieldValue, $raw, $unserializing, $loadResult, $dontSave);
                                $loadResult->popPath();
                            }
                            else
                                $this->{$fieldName} = $fieldValue;
                        } catch (\Exception $e) {
                            $loadResult->addFieldTypeError($fieldName, null, $e);
                        }
                    }break;
                case 1: // campo de estado.

                case 4: // cualquier otro campo{
                    {
                        try {
                            $this->{$fieldName} = $fieldValue;
                        } catch (\Exception $e) {
                            if(isset($e->source)) {
                                $name=$e->source->__getFieldPath();
                                $loadResult->addFieldTypeError($name, null, $e);
                            }
                        }
                    }break;

                case 3: //campo relacion, con un path
                    {
                        try {
                            $loadResult->pushPath($this->{"*".$fieldName}->__getFieldPath());
                            $this->{$fieldName}[0]->loadFromArray($fieldValue, $raw, $unserializing, $loadResult, $dontSave);
                            $loadResult->popPath();
                        }
                        catch (\Exception $e) {
                                $loadResult->addFieldTypeError($fieldName, null, $e);
                            }
                    }break;
                case 5:{ // relaciones inversas.

                    $n=count($fieldValue);
                    for($k=0;$k<$n;$k++) {
                        try {
                            $loadResult->pushPath($this->{"*".$fieldName}->__getFieldPath()."/".$k);
                            $this->{$fieldName}[$k]->loadFromArray($fieldValue[$k], $raw, $unserializing, $loadResult, true);
                            $loadResult->popPath();
                        } catch (\Exception $e) {
                            $loadResult->addFieldTypeError($fieldName, null, $e);
                        }
                    }
                }break;
            }
        });
        // Despues de asignar campos, vemos si seguimos siendo un objeto nuevo.

        if(!$unserializing)
        {
            $errored=false;
            if(count($requiredFields)>0)
            {
                foreach($requiredFields as $f=>$v)
                {
                    $f=$this->__getField($f);
                    if(!$f->is_set()) {
                        if($loadResult) {
                            $exception=new BaseTypedException(BaseTypedException::ERR_REQUIRED_FIELD, ["field" => $f]);
                            $loadResult->addFieldTypeError($f->__getFieldPath(), null, $exception);
                            $f->__setErrored($exception);
                        }
                        $errored=true;
                    }
                }
            }
            if(!$errored && (!$loadResult || $loadResult->isOk())) {
                if ($dontSave == false)
                    $this->save();
            }
        }
        else {
            $this->endUnserialize();
            $this->cleanDirtyFields();
            $this->__loaded=true;

        }
        return $loadResult;
    }
    // Un BaseTypedObject siempre es controller para sus hijos.
    function __getControllerForChild()
    {
        return $this;
    }
    function __getPathPrefix()
    {
        return "/";
    }


    function copy($remoteObject)
    {

        $curVal=$remoteObject->normalizeToAssociativeArray();
        $this->loadFromArray($curVal,true,false);
    }


         // Normaliza a un array asociativo las siguientes versiones de objetos:
         // Si $fields es null, normaliza el objeto actual.
         // Si $fields es un BaseTypedObject, lo normaliza.
         // Si $fields tiene objetos como valores, supone que son campos de un modelo.
         // Finalmente, si ya esta normalizado, devuelve el normalizado.
         function normalizeToAssociativeArray($fields=null)
         {
             if($fields==null)
                 $fields=$this;

             if(is_a($fields,'\lib\model\BaseTypedObject'))
                 {
                     $fieldArray=[];
                     $fs=$fields->getFields();
                     if(is_array($fs)) {
                         foreach ($fs as $k => $v)
                             $fieldArray[$k] = $fields->{$k};
                     }
                 }
                 else
                 {
                     // Entonces $fields debe ser un array.Aun no sabemos si de valores, o de campos.
                     $fieldArray=[];
                     if(is_array($fields))
                     {
                         foreach($fields as $key=>$value)
                         {
                             if(is_object($value))
                                 $fieldArray[$key]=$value->getValue();
                             else
                                 $fieldArray[$key]=$value;
                         }
                     }
                 }
                 return $fieldArray;


         }

         function __validateArray($fields,\lib\model\ModelFieldErrorContainer $result=null)
         {
             return $this->__validate($fields,$result,true);
         }
         // FIELDS ES UN BASETYPEDOBJECT
         function __validate($fields,\lib\model\ModelFieldErrorContainer $result=null,$fromArray=false)
         {

             // Si no se envia un resultado, se crea uno.
             if(!$result)
                 $result=new \lib\model\ModelFieldErrorContainer();

             // Si no hay campos...Salimos.
             if(!$this->__fieldDef)
                 return true;

             if (count($result->getParsedFields()) > 0)
                 return true;


                 $fieldArray=$this->normalizeToAssociativeArray($fields);

             $targetModel=$this;
             $stateFieldName = $targetModel->getStateField();
             $nextState=null;
             // Tomamos todos los campos requeridos, para ver si tras validar los campos
             // recibidos, tendriamos un objeto con todos los campos requeridos.Esto incluye
             // los datos enviados, y los datos ya existentes en el objeto.
             $reqs=$this->__getRequiredFields();

             $requiredFields=[];
             $oldState=null;
             if($stateFieldName) {
                 $oldState = $targetModel->{"*" . $stateFieldName}->getLabel();
                 $nextState=$oldState;
             }

             for($k=0;$k<count($reqs);$k++)
                $requiredFields[$reqs[$k]]=1;
+             $validationMode=$this->__getValidationMode();

             $this->prioritizeChanges($fieldArray,function($fieldType,$fieldName,$fieldValue,$fieldDefinition)
             use (&$nextState,$stateFieldName,&$result,$targetModel,$fromArray,&$requiredFields,$oldState,$validationMode)
             {
                 switch ($fieldType) {
                     case 1:
                         {// campo de estado.

                             try {

                                 $newState = $fieldValue;
                                 if($oldState!=$newState) {

                                         if($targetModel->getStateDef()->isFinalState($oldState))
                                             $result->addFieldTypeError($stateFieldName,null,new BaseTypedException(BaseTypedException::ERR_CANT_CHANGE_FINAL_STATE));

                                         if(!$targetModel->getStateDef()->canTranslateTo($newState))
                                             $result->addFieldTypeError($stateFieldName,null,new BaseTypedException(BaseTypedException::ERR_CANT_CHANGE_STATE_TO));
                                 }
                                 $nextState=$targetModel->getStateDef()->getStateLabel($newState);
                                 $reqs=$this->__getRequiredFields($nextState);
                                 $requiredFields=[];
                                 for($k=0;$k<count($reqs);$k++)
                                    $requiredFields[$reqs[$k]]=1;
                                 unset($requiredFields[$fieldName]);
                             } catch (\Exception $e) {
                                 $result->addFieldTypeError($stateFieldName, null, $e);
                             }
                         }break;
                     case 2:
                     case 4: // cualquier otro campo{
                         {
                             // Si es una relacion, y se le ha asignado un array, es que se esta asignando el modelo remoto,
                             // no el valor del campo relacion.
                            $type=$this->{"*".$fieldName};
                             if($type->equals($fieldValue))
                                 break;
                             if($nextState!==null)
                             {
                                if(!$this->__stateDef->isEditableInState($fieldName,$nextState))
                                    $result->addFieldTypeError($fieldName,$fieldValue,new \lib\model\BaseTypedException(\lib\model\BaseTypedException::ERR_NOT_EDITABLE_IN_STATE,["field"=>$fieldName]));
                             }
                             else {
                                 if (!$type->__isEditable()) {
                                     $result->addFieldTypeError($fieldName, $fieldValue, new \lib\model\BaseTypedException(\lib\model\BaseTypedException::ERR_NOT_EDITABLE_IN_STATE, ["field" => $fieldName]));
                                     break;
                                 }
                             }
                             if($fieldType==2 && is_array($fieldValue)) {
                                 $this->{"*".$fieldName}->validate($fieldValue, $result, $fromArray);
                                 break;
                             }
                             try{
                                 $localField=$targetModel->__getField($fieldName);
                                 if($fieldType==4)
                                    $localField->validate($fieldValue);
                                 else
                                 {
                                     $relType=\lib\model\types\TypeFactory::getType($fieldName,$localField->getDefinition(),$this,null,$validationMode);
                                     $relType->validate($fieldValue);
                                 }
                             }catch(\Exception $e)
                             {
                                 $result->addFieldTypeError($fieldName, $fieldValue, $e);

                             }
                             if($stateFieldName && $result->isOk()) {
                                 $currentValue = $targetModel->{"*".$stateFieldName}->getLabel();
                                 // Si se quiere cambiar el valor, hay que comprobarlo contra el sistema de estados.
                                 if ($currentValue != $fieldValue) {
                                     if (!$targetModel->getStateDef()->isEditableInState($fieldName,$nextState)) {
                                         $result->addFieldTypeError($fieldName, $fieldValue,
                                             new BaseTypedException(BaseTypedException::ERR_NOT_EDITABLE_IN_STATE,
                                                 array("field" => $fieldName, "state" => $targetModel->__stateDef->getCurrentState())
                                             ));
                                     }
                                 }
                             }
                             if($result->isOk())
                                unset($requiredFields[$fieldName]);
                             /*if ($required && $targetModel->{"*".$fieldName}->isEmptyValue($fieldValue)) {
                                     $e = new BaseTypedException(BaseTypedException::ERR_REQUIRED_FIELD, array("name" => $fieldName));
                                     $result->addFieldTypeError($fieldName, "", $e);
                             }*/
                         }
                         break;

                     case 3: //campo relacion, con un path
                         {

                            // Hay 2 casos posibles.Uno es una relacion directa, y otra, inversa.
                             // Las directas apuntan a 1 solo modelo remoto.
                             // Las inversas, pueden apuntar a N modelos remotos.
                             $result->pushPath($fieldName);
                             $targetModel->{$fieldName}[0]->__validate($fieldValue, $result,$fromArray);
                             if($result->isOk())
                                unset($requiredFields[$fieldName]);
                             $result->popPath();
                         }
                         break;
                     case 5:{ // relaciones inversas.

                         $n=count($fieldValue);
                         $result->pushPath($fieldName);
                         foreach($fieldValue as $k=>$v) {
                             $result->pushPath($k);
                             $targetModel->{$fieldName}[$k]->__validate($v, $result,$fromArray);
                             $result->popPath();
                         }
                         $result->popPath();
                     }break;
                 }
             });

             if(count($requiredFields)>0)
             {
                 foreach($requiredFields as $f=>$v)
                 {
                     $f=$targetModel->__getField($f);
                     if(!$f->is_set()) {
                         $exception=new BaseTypedException(BaseTypedException::ERR_REQUIRED_FIELD, ["field" => $f]);
                         $result->addFieldTypeError($f->__getControllerPath(), null, $exception);
                         $f->__setErrored($exception);
                     }
                 }
             }
            // Finalmente, limpiamos campos sucios.
             //$targetModel->__dirtyFields=[];
             //$targetModel->__isDirty=false;

             return $result;
         }
         function save()
         {

             if($this->__changingState)
             {
                 // No se permite hacer un save si estamos enmedio de un cambio de estado.
                 throw new BaseTypedException(BaseTypedException::ERR_PENDING_STATE_CHANGE,["newState"=>$this->__newState]);
             }
             $this->__isDirty=false;
             foreach($this->__dirtyFields as $key=>$value)
                 $value->__setDirty(false);
             $this->__dirtyFields=[];
         }


    function __transferFields($modelName)
    {
        $result=[];
        foreach($this->__fieldDef as $k=>$v)
        {
            if(isset($v["MODEL"]) && $v["MODEL"]==$modelName && isset($v["FIELD"]))
            {
                $result[$v["FIELD"]]=$this->{$k};
            }
        }
        return $result;
    }
    function __filterFields($key,$value)
    {
        $result=[];
        foreach($this->__fieldDef as $k=>$v)
        {
            if(isset($v[$key]) && $v[$key]==$value)
                $result[]=$k;
        }
        return $result;
    }
    function getRequiredPermissions($action)
    {

        if($this->__stateDef->hasState())
        {
            $sDef=$this->__stateDef->getRequiredPermissions($action);
            if($sDef!==null)
                return $sDef;
        }
        if(isset($this->__definition["PERMISSIONS"]) && isset($this->__definition["PERMISSIONS"][$action]))
            return $this->__definition["PERMISSIONS"][$action];
        return [["TYPE"=>\lib\model\permissions\AclManager::PERMISSIONSPEC_PUBLIC]];
    }
}
