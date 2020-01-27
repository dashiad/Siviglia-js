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
    const ERR_PENDING_STATE_CHANGE=17;
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
        protected $__changingState=false;
        protected $__pendingRequired=[];
        protected $__allowRead=false;


        function __construct($definition)
        {
                $this->__objectDef=$definition;
                $this->__fieldDef=& $this->__objectDef[$this->getFieldsKey()];
                $this->__stateDef=new \lib\model\states\StatedDefinition($this);

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


        function __getField($fieldName,$inAliases=false)
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
                    if(strpos($fieldName,"/")!==false)
                    {
                        $remField=$this->__findField($fieldName);
                        if($remField)
                            return $remField;
                    }
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

        // Prioritiza el orden en el que hay que aplicar un callback.
        // El orden es: primero, campo propio de estado.
        // Segundo, relaciones, y posibles paths asociados a esas relaciones
        // Finalmente, el resto de campos.

        function prioritizeChanges($dataOrObj,$callback)
        {
            if(is_a($dataOrObj,'\lib\model\BaseTypedObject'))
            {
                $data=$dataOrObj->normalizeToAssociativeArray();
            }
            else
                $data=$dataOrObj;
            $keys=array_keys($data);
            $localFields=[];
            $relationships=[];
            $relationshipPaths=[];
            $invRelationships=[];
            $referencedModel=$this;
            $stateField=$referencedModel->getStateField();
            $state=null;
            foreach($data as $k=>$v)
            {
                if(strpos($k,"/")>0)
                {
                    $path=explode("/",$k);
                    $relation=array_shift($path);
                    $f=$referencedModel->__getField($relation,true);
                    if($f->isInverseRelation()) {
                        $index = array_shift($path);
                        if(!is_numeric($index))
                            throw new BaseTypedException(BaseTypedException::ERR_INVALID_PATH,["path"=>$index."/".implode("/",$path)]);
                        $subPath=implode("/",$path);
                        $invRelationships[$relation][$index][$subPath]=$v;
                    }
                    else {
                        $subPath = implode("/", $path);
                        $relationshipPaths[$relation][$subPath] = $v;
                    }
                    continue;
                }
                if($k==$stateField) {
                    $state = $k;
                    continue;
                }
                $f = $referencedModel->__getField($k,true);

                if(!$f->isRelation())
                    $localFields[$k]=$v;
                else {
                    // La siguiente linea significa:
                    // El campo a validar actual es una relacion inversa, pero no es un path.
                    // Es decir, el valor tiene que ser el asignable a una relacion inversa, es decir, un array
                    // de modelos.
                    // Al asignar directamente el valor, tenemos que lo que queremos es establecer el valor de la relacion,
                    // es decir, borrar los elementos relacionados actuales, y establecer los especificados.
                    // Pero, principalmente, hay que ver que como $v es un array, esta especificacion de relaciones inversas es
                    // compatible con la generada usando paths dentro del nombre de campo.
                    if($f->isInverseRelation())
                        $invRelationships[$k]=$v;

                    else
                        $relationships[$k] = $v;
                }
            }
            // Primero, se cambia el estado.
            if($state)
            {
                call_user_func($callback,1,$state,$data[$state],$this->__fieldDef["state"]);
            }
            // Segundo, se itera por los campos que sean relaciones, y que estan tanto como campos simples, como paths.
            foreach($relationships as $key=>$value)
            {
                    call_user_func($callback, 2, $key, $value, $referencedModel->__getField($key));
            }
            // Tercero, todos los campos "finales" que pertenecen a este modelo.
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
        // Si raw es true, el valor se asigna incluso si la validacion da un error.
        function loadFromArray($data,$raw=false,$unserializing=true,$loadResult=null)
        {
            // Cargar de un array se tiene que hacer en varias fases.
            // La primera fase, es detectar paths. Si hay paths, hay que cargar primero
            // los elementos propios, y luego los remotos. Asi, evitamos que las relaciones
            // pierdan sus datos.
            // Esto es porque si A es un objeto nuevo, que se relaciona con B (que ya existe), e
            // intentamos modificar B, primero hay que asignar la relacion en A, y luego modificar B,
            // ya que de otro modo, estariamos creando un nuevo B, y luego lo reasignariamos.
            // Por otro lado, primero hay que asignar el campo estado, si existe, y luego, el resto.
            $data=$this->normalizeToAssociativeArray($data);
            if(!$unserializing)
            {
                $result=$this->__validateArray($data,$loadResult);
                if(!$result->isOk())
                {
                    throw new \lib\model\BaseTypedException(\lib\model\BaseTypedException::ERR_LOAD_DATA_FAILED);
                }
            }
            if($unserializing)
                $this->beginUnserialize();

            $this->prioritizeChanges($data,function($fieldType,$fieldName,$fieldValue,$fieldDefinition)
            use ($raw,$unserializing,$loadResult)
            {
                switch($fieldType)
                {
                    case 2: // Campo relacion, sin path
                        {
                            if(is_array($fieldValue))
                                $this->{$fieldName}[0]->loadFromArray($fieldValue,$raw,$unserializing,$loadResult);
                            else
                                $this->{$fieldName}=$fieldValue;
                        }break;
                    case 1: // campo de estado.

                    case 4: // cualquier otro campo{
                        {
                            $this->{$fieldName}=$fieldValue;
                        }break;

                    case 3: //campo relacion, con un path
                        {
                            $this->{$fieldName}[0]->loadFromArray($fieldValue,$raw,$unserializing,$loadResult);
                        }break;
                    case 5:{ // relaciones inversas.
                        $n=count($fieldValue);
                        for($k=0;$k<$n;$k++)
                            $this->{$fieldName}[$k]->loadFromArray($fieldValue[$k],$raw,$unserializing,$loadResult);
                    }break;
                }
            });

            if(!$unserializing)
            {
                $this->save();
            }
            else {
                $this->endUnserialize();
                $this->__data=$data;
                $this->__loaded=true;
                $this->cleanDirtyFields();
            }
            return $loadResult;
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
            if($this->__changingState && !$this->__allowRead)
                throw new BaseTypedException(BaseTypedException::ERR_PENDING_STATE_CHANGE,["state"=>$this->newState,"extra"=>$varName]);
            $reference=false;
            if($varName[0]=="*") {
                $reference = true;
                $varName=substr($varName,1);
            }
            $field=$this->__findField($varName);
            if($reference)
                return $field->getType();
            $parent=$field->getModel();
            $gMethod="get_".$field->getName();
            if(method_exists($parent,$gMethod)) {
                return $parent->$gMethod();
            }
            if($field)
            {
            if($reference)
                return $field->getType();
            return $field->get();

            }
            throw new BaseTypedException(BaseTypedException::ERR_NOT_A_FIELD,array("field"=>$varName));
        }

        function __findField($varName)
        {
            $parts=explode("/",$varName);
            if($parts[0]=="") {
                array_splice($parts,0,1);
            }
            $nParts=count($parts);
            if($nParts==1) {
                return $this->__getField($varName,true);
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

            $this->__allowRead=true;
            if(isset($this->__fieldDef[$varName]))
            {
                // Se comprueba primero que el valor del campo es diferente del que tenemos actualmente.

                if($this->{"*".$varName}->equals($value)) {
                    $this->__allowRead=false;
                    return;
                }

                if($this->__stateDef->hasState && $this->isLoaded())
                {
                    if(!$this->__stateDef->isEditable($varName) && $value!=$this->{$varName})
                    {
                        $this->__allowRead=false;
                        throw new BaseTypedException(BaseTypedException::ERR_NOT_EDITABLE_IN_STATE,array("field"=>$varName,"state"=>$this->__stateDef->getCurrentState()));
                    }
                }
                $checkMethod="check_".$varName;
                if(method_exists($this,$checkMethod)) {
                    if(!$this->$checkMethod($value)) {
                        $this->__allowRead=false;
                        throw new BaseTypedException(BaseTypedException::ERR_INVALID_VALUE, array("field" => $varName, "value" => $value));
                    }

                }

                $processName="process_".$varName;
                $existsProcess=method_exists($this,$processName);

                if($existsProcess)
                    $value=$this->$processName($value);
            }
            else
            {
                $remField=$this->__findField($varName);
                if($remField)
                {
                    $model=$remField->getModel();
                    $model->__set($remField->getName(),$value);
                    $this->__allowRead=false;
                }
            }

            // Ahora hay que tener cuidado.Si lo que se esta estableciendo es el campo que define el estado
            // de este objeto, no hay que copiarlo.Hay que meterlo en una variable temporal, hasta que se haga SAVE
            // del objeto.El nuevo estado aplicará a partir del SAVE.Asi, podemos cambiar otros campos que era posible
            // cambiar en el estado actual del objeto.

            $targetField=$this->__getField($varName);
            $parentModel=$targetField->getModel();
            if($parentModel->__stateDef->hasState)
            {
                if($varName==$parentModel->__stateDef->getStateField())
                {
                    if (isset($parentModel->__dirtyFields[$varName])) {
                        $this->__allowRead=false;
                        throw(new BaseTypedException(BaseTypedException::ERR_DOUBLESTATECHANGE));
                    }
                    $parentModel->__stateDef->setOldState($parentModel->__getField($varName)->get());
                    $parentModel->__setChangingState($value);

                }
                else {
                        $targetField->set($value);
                }

            }
            else {
                    $targetField->set($value);
            }
            if($parentModel==$this)
                $parentModel->addDirtyField($varName);
            $this->__allowRead=false;
        }
        function __setChangingState($newState)
        {
            $this->__changingState=true;
            $this->__newState=$newState;
            $this->__pendingRequired=$this->__stateDef->getRequiredFields($newState);
            $this->__checkStateChangeCompleted();
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
                if($this->__getField($k)->isDefinedAsRequired()==true)
                    $required[]=$k;
            }
            return $required;
        }


        function __checkStateChangeCompleted($field=null)
        {
            if($this->__dirtyFields==null)
                return;
            $newPending=[];
            for($k=0;$k<count($this->__pendingRequired);$k++)
            {
                $reqField=$this->__pendingRequired[$k];
                $f=$this->__getField($reqField);
                if($f->is_set())
                    continue;
                if(isset($this->__dirtyFields[$reqField]))
                    continue;
                $newPending[]=$reqField;
            }
            if(count($newPending)==0)
            {
                $this->__changingState=false;
                $this->__stateDef->changeState($this->__newState);
            }
            $this->__pendingRequired=$newPending;
        }
        function __getPendingRequired()
        {
            return $this->__pendingRequired;
        }

        function copy(& $remoteObject)
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
                                 $fieldArray[$key]=$value->getModel()->{$key};
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

             $this->prioritizeChanges($fieldArray,function($fieldType,$fieldName,$fieldValue,$fieldDefinition)
             use (&$nextState,$stateFieldName,&$result,$targetModel,$fromArray,&$requiredFields,$oldState)
             {
                 switch ($fieldType) {
                     case 1:
                         {// campo de estado.

                             try {

                                 $newState = $fieldValue;
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

                             if($this->{"*".$fieldName}->equals($fieldValue))
                                 break;

                             if(!$targetModel->getStateDef()->isEditableInState($fieldName,$nextState))
                             {
                                 $result->addFieldTypeError($fieldName,$fieldValue,new \lib\model\BaseTypedException(\lib\model\BaseTypedException::ERR_NOT_EDITABLE_IN_STATE,["field"=>$fieldName]));
                                 break;
                             }
                             if($fieldType==2 && is_array($fieldValue)) {
                                 $this->{$fieldName}[0]->__validate($fieldValue, $result, $fromArray);
                                 break;
                             }
                             try{
                                 $localField=$targetModel->__getField($fieldName);
                                 if($fieldType==4)
                                    $localField->getType()->validate($fieldValue);
                                 else
                                 {
                                     $relType=\lib\model\types\TypeFactory::getType($targetModel,$localField->getDefinition());
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
                     if(!$targetModel->__getField($f)->is_set())
                        $result->addFieldTypeError($f,null,new BaseTypedException(BaseTypedException::ERR_REQUIRED_FIELD,["field"=>$f]));
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
             $this->__dirtyFields=[];
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
             if($this->__changingState)
                 $this->__checkStateChangeCompleted();
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
