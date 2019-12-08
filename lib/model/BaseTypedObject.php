<?php namespace lib\model;


use lib\model\types\BaseTypeException;

class PathObjectException extends \lib\model\BaseException {
    const ERR_PATH_NOT_FOUND=1;
    const ERR_NO_CONTEXT=2;
}

class PathObject {
    var $tempContext;
   function getPath($path, $context)
   {
       if($path[0]!='/')
           $path='/'.$path;
       $path=$this->parseString($path,$context);
       if($context)
           $context->setCaller($this);

       $parts=explode("/",$path);
       $pathLength=count($parts);
       return PathObject::_getPath($this,$parts,0,$context,$path,$pathLength);
   }
   private function psCallback($match)
   {
       return $this->getPath($match[1],$this->tempContext);
   }
    // La siguiente funcion gestiona paths dentro de paths.
   function parseString($str,$context)
   {
       $this->tempContext=$context;
       return preg_replace_callback("/{\%([^%]*)\%}/",array($this,"psCallback"),$str);
   }

   static function _getPath(& $obj,$path,$index,$context,& $origPath,$pathLength=-1)
   {
       if(!isset($obj))
           throw new PathObjectException(PathObjectException::ERR_PATH_NOT_FOUND,array("path"=>$origPath,"index"=>$index));
      if($index+1==$pathLength)
            return $obj;

      if(is_string($path[$index+1]))
      {
        $c=$path[$index+1][0];
        if($c=="@")
        {
            if(!$context)
            {
                throw new PathObjectException(PathObjectException::ERR_NO_CONTEXT);
            }
            $variable=$path[$index+1];
            $onListener=null;

            $caller=$context->getCaller();
            $path[$index+1]=substr($path[$index+1],1);
            $onListener=$caller->{$path[$index+1]};

            if($onListener)
                $tempObj=$caller;
            else
                $tempObj=$context;



            if(is_array($tempObj))
            {
                $val=$tempObj[$path[$index+1]];
                if(!isset($val))
                    throw new PathObjectException(PathObjectException::ERR_PATH_NOT_FOUND,array("path"=>$origPath,"index"=>$index+1));
            }
            else
            {
                if(is_object($tempObj))
                {
                    $val=$tempObj->{$path[$index+1]};
                    if(!isset($val) || $val===null)
                    if($tempObj instanceof \ArrayAccess)
                    {
                        $val=$tempObj[$path[$index+1]];
                        if(!isset($val))
                            throw new PathObjectException(PathObjectException::ERR_PATH_NOT_FOUND,array("path"=>$origPath,"index"=>$index+1));
                    }
                    else
                        throw new PathObjectException(PathObjectException::ERR_PATH_NOT_FOUND,array("path"=>$origPath,"index"=>$index+1));
                }
            }
            if(is_object($val) || is_array($val))
            {

                 $index++;
                 $obj=$val;
            }
            else
            {
                $path[$index+1]=$val;
            }
            return PathObject::_getPath($obj,$path,$index,$context,$origPath,$pathLength);
        }
    }
    if(is_array($obj) || is_a($obj,"ArrayAccess"))
    {

        if(isset($obj[$path[$index+1]]))
        {
            return PathObject::_getPath($obj[$path[$index+1]],$path,$index+1,$context,$origPath,$pathLength);
        }
        throw new PathObjectException(PathObjectException::ERR_PATH_NOT_FOUND,array("path"=>$origPath,"index"=>$index+1));
    }

    if(is_object($obj))
    {

        $propName=$path[$index+1];
        $val=$obj->{$propName};
        if(!(is_object($val) || is_array($val)))
        {
            if(method_exists($obj,$val))
            {
                $result=$obj->{$val}();
                return PathObject::_getPath($result,$path,$index+1,$context,$origPath,$pathLength);
            }
            return $val;
        }
        else
             return PathObject::_getPath($val,$path,$index+1,$context,$origPath,$pathLength);
    }
     else
        return $obj;

    //return  Ecija.Dom.getPath(obj[path[index+1]],path,index+1,context,currentObject,listener);
}
}

 class SimpleContext
 {
        var $caller;
        var $currentModel=null;
        function setCaller($obj){$this->caller=$obj;}
        function getCaller(){return $this->caller;}
 }

 class SimplePathObject extends \lib\model\PathObject
 {
        function addPath($nodeName,& $objectInstance)
        {
            $this->{$nodeName}=& $objectInstance;
        }
 }

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
}

class BaseTypedObject extends PathObject
{
        protected $__fieldDef;
        protected $__data;
        protected $__fields;
        protected $__objectDef;
        protected $__loaded=0;
        protected $__serializer=null;
        protected $__isDirty=false;
        protected $__dirtyFields=array();
        protected $__stateDef;
        protected $__oldState=null;
        protected $__newState=null;
        protected $__key=null;

        // referencedModel indica sobre que modelo tenemos que calcular operaciones, cuando este
        // baseTypedObject nerecsita referenciar a otro para ejecutar esas operaciones.
        // El ejemplo es Action , que es un BaseTypedObject, que requiere comprobar estado en base a otro
        // objeto, un modelo externo, que tiene estado.
        // En ese caso, la definicion de estado, no es la del propio BaseTypedObject (que es una accion), sino
        // de un objeto remoto (el modelo al que se refiere).
        protected $__referencedModel;
        function __construct($definition)
        {

                $this->__objectDef=$definition;
                $this->__fieldDef=& $this->__objectDef[$this->getFieldsKey()];
                $this->__stateDef=new \lib\model\states\StatedDefinition($this);
                $this->__referencedModel=$this;
                $this->__key=null;
                if (isset($this->__objectDef["INDEXFIELDS"]))
                    $this->__key = new ModelKey($this, $this->__objectDef);


        }
        function getFieldsKey()
        {
            return "FIELDS";
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
        function getDefinition() {
                return $this->__objectDef;
        }

        function __getFields()
        {
               foreach($this->__fieldDef as $key=>$value)
                    $this->__getField($key);
               return $this->__fields;
        }


        function __getField($fieldName)
        {
            if(!isset($this->__fields[$fieldName]))
            {
                if(isset($this->__fieldDef[$fieldName]))
                {
                    $this->__fields[$fieldName]=\lib\model\ModelField::getModelField($fieldName,$this,$this->__fieldDef[$fieldName]);
                }
                else
                {
                    // Caso de "path"
                    if(strpos($fieldName,"/")>=0)
                    {
                        $remField=$this->__findRemoteField($fieldName);
                        if($remField)
                            return $remField;
                    }

                    include_once(PROJECTPATH."/lib/model/BaseModel.php");
                    throw new \lib\model\BaseTypedException(BaseTypedException::ERR_NOT_A_FIELD,array("name"=>$fieldName));
                }
            }
            return $this->__fields[$fieldName];
        }
        function __getFieldNames()
        {
            return array_keys($this->__fields);
        }
        function & __getFieldDefinition($fieldName)
        {
            if(isset($this->__fieldDef[$fieldName]))
                return $this->__fieldDef[$fieldName];

            throw new BaseTypedException(BaseTypedException::ERR_NOT_A_FIELD,array("name"=>$fieldName));

        }
        // Usado para los aliases de BaseModel
        function & __addField($fieldName,$definition)
        {
            $this->__fields[$fieldName]=\lib\model\ModelField::getModelField($fieldName,$this,$definition);
            return $this->__fields[$fieldName];

        }
        function __setSerializer($serializer)
        {
            $this->__serializer=$serializer;
        }
        function beginUnserialize()
        {
            $this->disableStateChecks();
        }
        function endUnserialize()
        {
            $this->enableStateChecks();
            $this->__loaded=true;
            $this->cleanDirtyFields();
        }
        // Si raw es true, el valor se asigna incluso si la validacion da un error.
        function loadFromArray($data,$raw=false)
        {
            $fields=$this->__getFields();
            if(!$fields)
                return;

            foreach ($fields as $key => $value)
                $value->setValue(isset($data[$key])?$data[$key]:null);

            if(!$raw)
            {
                $result=$this->__validateArray($data,null);
                if(!$result->isOk())
                {
                    throw new BaseTypedException(BaseTypedException::ERR_LOAD_DATA_FAILED);
                }
            }
            $this->beginUnserialize();
            // TODO : Tener en cuenta el campo estado.
            foreach($fields as $key=>$value)
            {
                if(!isset($data[$key]))
                    continue;

                $this->{$key}=$data[$key];

            }
            $this->endUnserialize();
            $this->__data=$data;
            $this->__loaded=true;
            $this->cleanDirtyFields();
        }
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
        function __get($varName)
        {
            if($varName[0]=="*")
            {
                $varName=substr($varName,1);

                return $this->__getField($varName)->getType();
            }
            if(isset($this->__fieldDef[$varName]))
            {
                $gMethod="get_".$varName;
                if(method_exists($this,$gMethod)) {
                    return $this->$gMethod();
                }
                return $this->__getField($varName)->get();
            }
            $remoteField=$this->__findRemoteField($varName);
            if($remoteField)
                return $remoteField->get();

            throw new BaseTypedException(BaseTypedException::ERR_NOT_A_FIELD,array("field"=>$varName));
        }
        function __findRemoteField($varName)
        {
            $parts=explode("/",$varName);
            if($parts[0]=="") {
                array_splice($parts,0,1);
            }
            $nParts=count($parts);
            if($nParts==1) {
                return false;
            }
            $context=new SimpleContext();
            // Si el path es, por ejemplo, a/b/c, queremos encontrar a/b , y pedirle el campo c.
            // Por eso se extrae y se guarda el ultimo elemento.
            $lastField=array_splice($parts,-1,1);
            $result=$this->getPath("/".implode("/",$parts),$context);
            if(!is_object($result)) {
                throw new BaseTypedException(BaseTypedException::ERR_INVALID_PATH,array("path"=>$varName));
            }
            // Por fuerza, el objeto $result tiene que ser un objeto relacion.Por lo tanto, hay que obtener el remote object, y a este, pedirle
            // el ultimo campo que hemos guardado previamente.
            if(is_a($result,'\lib\model\ModelBaseRelation')) {
                return $result->offsetGet(0)->__getField($lastField[0]);
            }

            return $result->__getField($lastField[0]);
        }
        function setFields($fields)
        {
            foreach($fields as $key => $value)
            {

                $this->__set($key,$value);
            }

        }

        function getFields()
        {
            return $this->__fields;
        }

        function __set($varName,$value) {


            if(isset($this->__fieldDef[$varName]))
            {
                // Se comprueba primero que el valor del campo es diferente del que tenemos actualmente.

                if($this->{$varName}===$value)
                    return;

                if($this->__stateDef->hasState && $this->isLoaded())
                {
                    if(!$this->__stateDef->isEditable($varName) && $value!=$this->{$varName})
                    {

                        throw new BaseTypedException(BaseTypedException::ERR_NOT_EDITABLE_IN_STATE,array("field"=>$varName,"state"=>$this->__stateDef->getCurrentState()));
                    }
                }
                $checkMethod="check_".$varName;
                if(method_exists($this,$checkMethod)) {
                    if(!$this->$checkMethod($value))
                        throw new BaseTypedException(BaseTypedException::ERR_INVALID_VALUE,array("field"=>$varName,"value"=>$value));

                }

                $processName="process_".$varName;
                $existsProcess=method_exists($this,$processName);

                if($existsProcess)
                    $value=$this->$processName($value);
            }
            else
            {
                $remField=$this->__findRemoteField($varName);
                if($remField)
                {
                    return $remField->getModel()->__set($remField->getName(),$value);
                }
            }

            // Ahora hay que tener cuidado.Si lo que se esta estableciendo es el campo que define el estado
            // de este objeto, no hay que copiarlo.Hay que meterlo en una variable temporal, hasta que se haga SAVE
            // del objeto.El nuevo estado aplicará a partir del SAVE.Asi, podemos cambiar otros campos que era posible
            // cambiar en el estado actual del objeto.

            $targetField=$this->__getField($varName);
            if($this->__stateDef->hasState)
            {
                if($varName==$this->__stateDef->getStateField())
                {
                    if (isset($this->__dirtyFields[$varName]))
                        throw(new BaseTypedException(BaseTypedException::ERR_DOUBLESTATECHANGE));
                    $this->__stateDef->setOldState($this->__getField($varName)->get());
                    $this->__stateDef->changeState($value);
                    // El cambiar el estado en si, lo hace la definicion de estados, en el metodo changeState
                }
                else
                    $targetField->set($value);

            }
            else
                $targetField->set($value);

            $targetField->getModel()->addDirtyField($varName);
        }

        function copy(& $remoteObject)
        {

             $remFields=$remoteObject->__getFields();
             if($this->__stateDef->hasState)
                 $stateField=$this->getStateField();
             else
                 $stateField='';
             foreach($remFields as $key=>$value)
             {

                 $types=$value->getTypes();
                 foreach($types as $tKey=>$tValue)
                 {

                     try{
                         $field=$this->__getField($tKey);
                         if($tKey==$stateField)
                             $this->newState=$tValue->get();
                         else
                            $field->copyField($tValue);
                     }catch(BaseTypedException $e)
                     {
                         if($e->getCode()==BaseTypedException::ERR_NOT_A_FIELD)
                         {
                             // El campo no existe.No se copia, pero se continua.
                             continue;
                         } // En cualquier otro caso, excepcion.
                         else
                             throw $e;
                     }
                 }
             }

             $this->__dirtyFields=$remoteObject->__dirtyFields;

             $this->__isDirty=$remoteObject->__isDirty;
         }

         function setReferencedModel($model)
         {
             $this->__referencedModel=$model;
         }
         function getReferencedModel()
         {
             return $this->__referencedModel;
         }
         function normalizeToAssociativeArray($fields=null)
         {
             $fieldArray=null;

             if(is_object($fields) && is_a($fields,'\lib\model\BaseTypedObject'))
             {
                 $fieldArray=$fields->getFields();
             }
             else
             {
                 if($fields==null)
                     $fields=$this->__fields;
                 if(is_array($fields))
                 {
                     foreach($fields as $key=>$value)
                     {
                         // Si no es un campo nuestro, continuamos.
                         if(!isset($this->__fieldDef[$key]))
                             continue;
                         if(is_a($value,'\lib\model\types\BaseType'))
                         {
                             if($value->hasValue())
                                 $fieldArray[$key]=$value->getValue();
                         }
                         else
                             $fieldArray[$key]=$value->getValue();
                     }
                 }
             }
             return $fieldArray;
         }
         function __validateArray($fields,\lib\model\ModelFieldErrorContainer $result=null)
         {
             return $this->__validate($fields,$result,true);
         }
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

             // Se normalizan los campos.Queremos solo y exclusivamente un array campo->valir.
             if($fromArray==false)
                $fieldArray=$this->normalizeToAssociativeArray($fields);
             else
                 $fieldArray=$fields;

             // Primero se normalizan todos los tipos, porque lo vamos a necesitar
             // para tener en cuenta el estado.
             foreach ($this->__fieldDef as $key => $value) {
                 if(isset($fieldArray[$key])) {

                     $newType = \lib\model\types\TypeFactory::getType($this, $value);

                         try {
                                 $newType->setValue($fieldArray[$key]);
                         } catch (\Exception $e) {
                             $result->addFieldTypeError($key, $fieldArray[$key], $e);
                         }
                     $result->addParsedField($key,$newType);
                 }
                 else {
                     $result->addParsedField($key,$this->{"*".$key});
                 }

             }

             $types=$result->getParsedFields();

             $targetModel=$this->getReferencedModel();
             $stateFieldName = $targetModel->getStateField();
             $nextState=null;
             $newState=null;

             if ($stateFieldName && isset($types[$stateFieldName])) {
                 $stateType = $types[$stateFieldName];

                 if (isset($fieldArray[$stateFieldName])) {
                     try {
                         $oldState=$targetModel->{$stateFieldName};
                         $newState = $stateType->get();
                         if($oldState!=$newState) {
                             if (isset($targetModel->__dirtyFields[$stateFieldName]))
                                 $result->addFieldTypeError($stateFieldName, null, new BaseTypedException(BaseTypedException::ERR_DOUBLESTATECHANGE));
                             else
                             {
                                 if($targetModel->getStateDef()->isFinalState($oldState))
                                     $result->addFieldTypeError($stateFieldName,null,new BaseTypedException(BaseTypedException::ERR_CANT_CHANGE_FINAL_STATE));

                                 if(!$targetModel->getStateDef()->canTranslateTo($newState))
                                     $result->addFieldTypeError($stateFieldName,null,new BaseTypedException(BaseTypedException::ERR_CANT_CHANGE_STATE_TO));
                             }
                         }
                         $nextState=$targetModel->getStateDef()->getStateLabel($newState);
                     } catch (\Exception $e) {
                         $result->addFieldTypeError($stateFieldName, null, $e);
                     }
                 }
                 else
                     $nextState=$targetModel->{"*".$stateFieldName}->getLabel();
             }

             foreach ($this->__fieldDef as $key => $value) {
                 $curVal=\io($fieldArray,$key,null);
                 $isset=false;
                 $newType=$types[$key];
                 if($curVal!=null)
                     $isset=true;
                 else
                 {
                     if(($newType->getFlags() & \lib\model\types\BaseType::TYPE_NOT_MODIFIED_ON_NULL) ||
                         (isset($value["UPDATE_ON_NULL"]) && !$value[$key]["UPDATE_ON_NULL"]))
                         $isset=true;
                 }
                 $reference=$newType->getTypeReference();
                 $targetField=$key;
                 if($reference)
                 {
                     $targetField=$reference["FIELD"];
                 }

                 // NOTE: si en el array inicial no estaba esta key, lo deserializamos con null.
                 // Esto es importante ya que si el campo es requerido, aunque no tuviera valor, podria haber
                 // un valor por defecto.
                 // Esta linea ya daria una excepcion, en caso de que se

                 if($stateFieldName) {
                     $currentValue = $targetModel->{"*" . $targetField}->getValue();
                     // Si se quiere cambiar el valor, hay que comprobarlo contra el sistema de estados.
                     if ($currentValue != $newType->getValue()) {
                         if (!$targetModel->getStateDef()->isEditableInState($targetField,$nextState)) {
                             $result->addFieldTypeError($key, $curVal,
                                        new BaseTypedException(BaseTypedException::ERR_NOT_EDITABLE_IN_STATE,
                                            array("field" => $targetField, "state" => $targetModel->__stateDef->getCurrentState())
                                        ));
                         }
                     }
                     $required = $targetModel->getStateDef()->isRequiredForState($targetField,$nextState);
                 }
                 else
                     $required = $targetModel->isRequired($key);

                 if ($required && !$isset) {
                     if (!$this->__fields[$key]->is_set()) {
                         $e = new BaseTypedException(BaseTypedException::ERR_REQUIRED_FIELD, array("name" => $key));
                         $result->addFieldTypeError($key, $curVal, $e);
                     }
                 }
             }
             return $result;
         }
         function save()
         {
             $this->__saveState();
             $this->__isDirty=false;
             $this->__dirtyFields=[];
         }
         function checkTransition()
         {
             if(!$this->__stateDef->hasState)
                 return true;
             if($this->__newState!==null)
             {
                 if($this->__stateDef->canTranslateTo($this->__newState))
                     return true;
                 throw new BaseTypedException(BaseTypedException::ERR_INVALID_STATE_TRANSITION,array("current"=>$this->__stateDef->getCurrentState(),"next"=>$this->__newState));
             }
             return true;
         }
         function __checkState()
         {
             $this->__stateDef->checkState();
         }
         function __saveState()
         {
             $this->__stateDef->reset();
         }
         function isDirty()
         {
             return $this->__isDirty;
         }

         function setDirty($dirty)
         {
             $this->__isDirty=$dirty;
             if(!$dirty)
                 $this->__dirtyFields=array();
         }

         function addDirtyField($fieldName)
         {
             $this->__isDirty=true;
             $this->__dirtyFields[$fieldName]=1;
         }

         function cleanDirtyFields()
         {
             $this->__isDirty=false;
             $this->__dirtyFields=array();
             $this->__stateDef->reset();
         }
         function getDirtyFields()
         {
             return $this->__dirtyFields;
         }
         function isRequired($fieldName)
         {
             $fieldDef=$this->__getField($fieldName)->getDefinition();
             // TODO: El modelo podria ser otro, no solo el actual.
             if(isset($fieldDef["MODEL"]) && isset($fieldDef["FIELD"]))
                 $fieldName=$fieldDef["FIELD"];
             return $this->__stateDef->isRequired($fieldName);
         }
         function isEditable($fieldName)
         {
             return $this->__stateDef->isEditable($fieldName);
         }
         function isFixed($fieldName)
         {
             return $this->__stateDef->isFixed($fieldName);
         }

    function disableStateChecks()
    {
        $this->__stateDef->disable();
    }
    function enableStateChecks()
    {
        $this->__stateDef->enable();
    }
    function getStateField()
    {
        return $this->__stateDef->getStateField();
    }
    function getStates()
    {
        return $this->__stateDef->getStates();
    }
    function getStateDef()
    {
        return $this->__stateDef;
    }

    function getStateId($stateName)
    {
        if (!$this->__objectDef["STATES"])
            return null;
        return array_search($stateName, array_keys($this->__objectDef["STATES"]["STATES"]));
    }

    function getStateLabel($stateId)
    {
        if (!$this->__objectDef["STATES"])
            return null;
        $statekeys = array_keys($this->__objectDef["STATES"]["STATES"]);
        //var_dump($statekeys[$stateId]);
        return $statekeys[$stateId];
    }

    function getState()
    {
        return $this->__stateDef->getCurrentState();
    }

    function __setRaw($fieldName,$data)
    {
        $field=$this->__getField($fieldName);
        $field->__rawSet($data);
        $this->__data[$fieldName]=$data;
    }
    function __getRaw()
    {
        return $this->__data;
    }

}
