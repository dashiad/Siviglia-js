<?php namespace lib\model\types\Base;
abstract class ModelBaseRelation extends \lib\model\types\BaseType implements \ArrayAccess
{

    const UN_SET=0;
    const SET=1;
    const DIRTY=3;
    const PENDING_REMOTE_SAVE=4;

    protected $remoteObject;
    protected $remoteTable;
    protected $types;
    protected $isAlias=false;
    protected $relation;
    protected $serializerData=array();
    protected $relationValues;

    protected $validationMode=\lib\model\types\BaseType::VALIDATION_MODE_COMPLETE;

	function __construct($name,$definition, $parentType, $value=null, $validationMode=null)
	{
	    $this->definition=$definition;
	    parent::__construct($name,$definition,$parentType,null,$validationMode);
        $this->remoteObject=$definition["MODEL"];
        if(!isset($definition["TABLE"]) && !isset($definition["REMOTE_MODEL"]))
        {
            // Solo se carga la definicion.
            $remoteDef=\lib\model\types\TypeFactory::getObjectDefinition($this->remoteObject);
            $this->remoteTable=$remoteDef["TABLE"];
            $definition["TABLE"] = $remoteDef["TABLE"];
        }
        $this->definition=$definition;
        //$this->__controller=$parentType;
        $this->relation=$this->createRelationFields();
        $this->relationValues=$this->createRelationValues();
        if($value!==null)
            $this->set($value,\lib\model\types\BaseType::VALIDATION_MODE_NONE);
	}
    abstract function createRelationValues();

    function createRelationFields()
    {
        return new RelationFields($this,$this->definition);
    }
    function getRemoteObject()
    {
        return $this->remoteObject;
    }
    function getRelation()
    {
         return $this->relation;
    }
    function getRaw()
    {
        $types=$this->relation->getTypes();
        foreach($types as $k=>$v)
            return $v->getValue();
    }
    function reset()
    {
        $this->relationValues->reset();
    }
    function getRemoteTable()
    {
		return $this->remoteTable;
    }
    function setAlias($alias)
    {
        $this-> isAlias=$alias;
    }
    function isAlias()
    {
        return $this->isAlias;
    }
    function getReference()
    {
        return $this;
    }
    function _getValue(){return $this->get();}
    function _setValue($val,$validationMode=null){return $this->set($val,$validationMode);}
    abstract function set($value,$validationMode=null);
    abstract function get();
    //abstract function loadRemote();
    abstract function loadCount();

    function cleanState()
    {
        $this->relation->cleanState();
    }
    // Implementacion de metodos de DataSource
    function fetchAll()
    {
        $this->loadRemote(null);
        return $this->relationValues;
    }
    function count()
    {
        return $this->relationValues->count();
    }

    function getIterator($rowInfo=null)
    {
        return $this->relationValues;
    }
    function isLoaded()
    {
        return $this->relationValues->isLoaded();
    }
    function countColumns()
    {
        return $this->relationValues->countColumns();
    }
    function getMetaData()
    {
        return $this->relationValues->getMetaData();
    }

    function isDirty()
    {
        return $this->relation->isDirty() || $this->relationValues->isDirty();
    }
    function onDirty()
    {
        $this->__controller->addDirtyField($this);
    }

    function setSerializer($ser)
    {
        $this->serializer=$ser;
    }

    function getSerializer()
	{
		return $this->__controller->__getSerializer();
	}

    public function offsetExists($offset)
    {
        if(!is_numeric($offset))
        {
            return $this->relationValues->count()>0;
        }
        return $this->relationValues->offsetExists($offset);
    }

    public function offsetGet( $offset )
    {
        if(!is_numeric($offset))
        {
            $val=$this->relationValues->offsetGet(0);
            return $val->{$offset};
        }
        return $this->relationValues->offsetGet($offset);

    }

    public function offsetSet( $offset , $value )
    {
        if(!is_numeric($offset)) {
            if ($offset === null) {
                // Se esta añadiendo a la relacion (relaciones inversas)
                $this->relationValues->add($value);

            } else {
                // se supone que se esta accediendo a un campo del primer valor de la relacion (relaciones directas)

                $val = $this->relationValues->offsetGet(0);
                $val->{$offset} = $value;
            }
        }
        else
            $this->relationValues->{$offset}=$value;
    }
    public function offsetUnset($offset)
    {
        $this->relationValues->offsetUnset($offset);
    }
    function getType($typeName=null)
    {
        $types=$this->relation->getTypes();
        if($typeName)
            return $types[$typeName];
        // TODO : Solo se retornal el primero!
        foreach($types as $key=>$value)
            return $value;
    }
    function getTypes()
    {
        return $this->relation->getTypes();
    }
    function getModel()
    {
        return $this->__controller;
    }

    function getLocalModel()
    {
        return $this->__controller;
    }
    function isInverseRelation()
    {
        return false;
    }
    function setSerializerData($serializerName,$data)
    {
        $this->serializerData[$serializerName]=$data;
    }
    function getSerializerData($serializerName)
    {
        return $this->serializerData[$serializerName];
    }
    function getRemoteTableQuery()
    {
        $table=$this->definition["TABLE"];
        if(!$table)
        {
            $table=$this->remoteTable;
        }

        $q=array(
            "TABLE"=>$table
            //"BASE"=>"SELECT * FROM ".$table
        );

        if(isset($this->definition["CONDITION"]))
        {
            if(is_array($this->definition["CONDITION"][0]))
                $conditions=$this->definition["CONDITION"];
            else
                $conditions=array($this->definition["CONDITION"]);
        }
        else
            $conditions=array();

        $q["CONDITIONS"]=$conditions;

        if(isset($this->definition["ORDERBY"]))
        {
            $q["ORDERBY"]=$this->definition["ORDERBY"];
            if(isset($this->definition["ORDERTYPE"]))
                 $q["ORDERTYPE"]=$this->definition["ORDERTYPE"];
        }

        return $q;
    }
    function getRelationQueryConditions($dontUseIndexes=false)
    {

        $q=$this->getRemoteTableQuery();
        $serializer=$this->getSerializer();
        $serType=$serializer->getSerializerType();
        if($dontUseIndexes==false)
        {
            $this->relation->getQueryConditions($q,$serializer);
        }

        return $q;
    }
    function getExtraConditions()
    {
        if(isset($this->definition["CONDITIONS"]))
            return $this->definition["CONDITIONS"];
        return null;
    }
    function setExtraConditions($conditions)
    {
        $this->definition["CONDITIONS"]=$conditions;
        // Para permitir encadenado
        return $this;
    }


    function loadRemote($itemIndex=0,$dontUseIndexes=false)
    {
        if(io($this->definition,"LOAD","")=="LAZY" && $itemIndex===null) {
            $this->relationValues->setLoaded();
            return true;
        }
        // Si el modelo padre es nuevo, simplemente, se crea una instancia del objeto remoto.

        if(!$this->relation->is_set()) { // && !$this->isInverseRelation())
            $this->relationValues->setLoaded();
            $count=$this->relationValues->count();
            if($itemIndex >= $count)
            {

                $remoteModel=$this->createRemoteInstance();
                $this->relationValues->loadItem($remoteModel,$itemIndex);
                $dummy= $this->relationValues[$itemIndex]; // Y se recoge, para marcarlo como accedido
                // Como estamos accediendo a campos, tenemos que decir al modelo padre que este campo puede estar sucio.
                // Por ello, lo establecemos a dirty.
                $this->relation->state=ModelBaseRelation::PENDING_REMOTE_SAVE;
                $this->setDirty();
            }
            return 1;
        }
        // Se compone una query
        $q=$this->getRelationQueryConditions($dontUseIndexes);
        if($itemIndex!==null) {
            $q["STARTINGROW"]=$itemIndex;
            $q["PAGESIZE"]=1;
        }
        $inst=$this->createRemoteInstance();
        $serializer=$inst->__getSerializer();
        $objects=$serializer->subLoad($q,$this);

        $nObjects=count($objects);
        if($nObjects > 0) {
            if($itemIndex===null) {
                // Estamos cargando del remoto. Esto no debe poner relationValues a dirty
                $this->relationValues->load($objects,false);
            }
            else {
                $this->relationValues->loadItem($objects[0],$itemIndex);
            }
        }

        return $nObjects;
    }

    function getRemoteTableIterator()
    {
        $oldLoad=$this->definition["LOAD"];
        $this->definition["LOAD"]="FULL";
        $oldValues=$this->relationValues;
        $this->relationValues=$this->createRelationValues();
        $this->loadRemote(null,true);
        $newVals=$this->relationValues;
        $this->relationValues=$oldValues;
        $this->definition["LOAD"]=$oldLoad;
        return $newVals;
    }

    function createRemoteInstance()
    {
        $remClass=$this->remoteObject;
       $ins=new $remClass(); // \lib\model\BaseModel::getModelInstance($this->remoteObject);
       $srcConds=$this->getExtraConditions();

       if($srcConds)
       {
            $nSrcConds=count($srcConds);
            for($j=0;$j<$nSrcConds;$j++)
            {
                $ccond=$srcConds[$j]["FILTER"];
                if(is_array($ccond))
                {
                    if(isset($ccond["F"]) && $ccond["OP"]=="=")
                        $ins->{$ccond["F"]}=$ccond["V"];
                }
            }
       }

       if($this->isInverseRelation())
       {
            $fields=$this->definition["FIELDS"];
            $model=$this->getModel();
            foreach($fields as $key=>$value)
            {
                $cField=$model->__getField($key);
                if($cField->is_set())
                    $ins->{$value}=$model->{$key};
            }
       }
       return $ins;
    }

    function unserialize($data)
    {
        $this->relation->load($data);
    }
    function setDirty($dirty=true)
    {
        $this->__isDirty=$dirty;
        if($this->__controller) {
            if ($dirty)
                $this->__controller->addDirtyField($this);
            else
                $this->__controller->removeDirtyField($this);
        }
    }
    function is_set()
    {
        return $this->relation->is_set() || $this->relation->state==ModelBaseRelation::PENDING_REMOTE_SAVE;
    }
    function isRelation()
    {
        return true;
    }


    function requiresUpdateOnNew()
    {
        // Para las relaciones "normales", es decir , A tiene una relacion con B, y estoy guardando A, siempre hay
        // que guardar primero B, obtener su valor, y copiarlo en A.
        // No es posible primero guardar A y luego hacer update de B.
        // Sin embargo, en las relaciones inversas y multiples, si que es necesario primero guardar A, y luego hacer update en B.
        // En la clase de relacion inversa, este metodo se sobreescribe, devolviendo siempre true.
        return $this->isInverseRelation();
        //return false;
    }

}


/**
 *  Class RelationFields
 *
 *  Campos que relacionan el modelo actual con el remoto.
 *
 *
 */


class RelationFields
{
    var $relObject;
    var $definition;
    var $state;
    var $nFields=0;
    var $types;
    var $fieldKey;
    var $waitingRemoteSave=false;
    var $is_set=false;
    var $rawVal=null;
    var $relationValues=null;

    function __construct(& $relObject,$definition)
    {
        $this->relObject=$relObject;
        $this->definition=$definition;
        $fields=$definition["FIELDS"]?$definition["FIELDS"]:(array)$definition["FIELD"];

        if(!\lib\php\ArrayTools::isAssociative($fields))
        {
            $fields=array($this->relObject->getName()=>$fields[0]);
        }
        $modelClassName=$relObject->getRemoteObject();
        foreach($fields as $key=>$value)
        {
            $this->fieldKey=$key;
            $this->nFields++;

            $this->types[$key]=\lib\model\types\TypeFactory::getRelationFieldTypeInstance(
                    $modelClassName,
                    $value,
                    $this->relObject->__getName(),
                    $this->relObject->getModel(),
                    null,
                    $relObject->getValidationMode());

            if(isset($definition["DEFAULT"]))
            {
                $this->types[$key]->apply($definition["DEFAULT"]);
            }

        }
        $this->definition["FIELDS"]=$fields;
        $this->state=ModelBaseRelation::UN_SET;

    }
    function getFields()
    {
        return $this->definition["FIELDS"]?$this->definition["FIELDS"]:(array)$this->definition["FIELD"];
    }

    function setRawVal($value)
    {
        $this->rawVal=$value;
        $this->state=ModelBaseRelation::SET;
    }
    function save()
    {
        foreach($this->types as $k=>$v)
            $v->save();

    }
    function getTypes()
    {
        return $this->types;
    }
    function copyField($type)
    {

        $hv2=$type->hasOwnValue();
        foreach($this->types as $curType)
        {
            // Aqui usamos __isEmpty porque aunque un container no este completo, tenemos que copiarlo.
            $hv1=!$curType->__isEmpty();
            if(!$hv1 && !$hv2) // Ninguno de los dos is_set
                return;
            if($hv1 && $hv2)
            {
                $val=$type->getValue();
                if($curType->equals($val))
                {
                    return;
                }
                $this->relObject->getModel()->{$this->relObject->getName()}=$val;
            }
            else
            {
                if(!$hv1 && $hv2)
                {
                    $val=$type->getValue();
                    // El valor se copia a traves del padre, ya que hay algunos tipos de campo (por ejemplo,
                    // los campos STATE), que al cambiar de valor, tienen repercusiones en el padre.
                    $myModel=$this->relObject->getModel();
                    $myModel->{$this->relObject->getName()}=$val;
                }
            else
            {
                foreach($this->types as $key=>$value)
                {

                         $value->clear();
                }
            }
        }
        }
        $this->setDirty();
    }

    function load($rawModelData)
    {
        // Aqui hay dos cosas conflictivas.is_set se refiere a si esta relacion tiene realmente un valor asociado.O sea, si no es null.
        // ModelBaseRelation::SET se refiere a si a este objeto se le han cargado valores o no.
        $this->state=ModelBaseRelation::UN_SET;
        $k=0;
        foreach($this->types as $key=>$value)
        {
            // Al deserializar una relacion, lo hacemos con validaciones segun especifique el modelo padre:

            if($rawModelData[$key])
                $value->apply($rawModelData[$key],$this->relObject->getModel()->getValidationMode());
            else
                continue;
            if($k==0)
                $this->rawVal=$rawModelData[$key];
            $k++;
        }
        if(isset($this->relationValues))
            $this->relationValues->reset();

        $this->state=ModelBaseRelation::SET;
    }


    function setFieldFromType($field,$targetType,$validationMode)
    {

        if($targetType->hasValue())
        {
             if(!$this->types[$field]->equals($targetType->getValue()))
             {
                $this->types[$field]->apply($targetType->getValue(),$validationMode);
             }
             else {
                 // el valor remoto de la relacion, coincide con el valor local.No cambiamos nada, para
                 // evitar en un bucle infinito
                 return;
             }
        }
        else
        {
            if($targetType->getFlags() & (\lib\model\types\BaseType::TYPE_SET_ON_SAVE | \lib\model\types\BaseType::TYPE_SET_ON_ACCESS))
                 $this->waitingRemoteSave=true;
            else
            {
                     foreach($this->types as $key=>$value)
                         $value->clear();
                     //throw new BaseModelException(BaseModelException::ERR_INCOMPLETE_KEY,array("model"=>$this->relObject->model->__getObjectName()));
            }
        }
        if($this->rawVal==null)
            $this->rawVal=$targetType->getValue();

        $v=$this->types[$field]->getValue();
        if(!$this->relObject->isInverseRelation()) {
            $this->relObject->getModel()->{"*" . $this->relObject->__getFieldPath()}->apply($v, \lib\model\types\BaseType::VALIDATION_MODE_NONE);

            $this->setDirty(true);
        }
        return true;
    }
    // Se copian los valores del modelo, a la relacion.
    function setFromModel($value,$validationMode=null)
    {
        foreach($this->types as $field=>$type)
        {
            // TODO : quitar esta absurdez de "if"
            if(is_a($this->relObject,'\lib\model\types\InverseRelation'))
            {
                $targetField = $value->__getField($field);
            }
            else {
                $targetField = $this->definition["FIELDS"][$field];
                $targetField = $value->__getField($targetField);
            }
            $targetType=$targetField;
            if(!$this->setFieldFromType($field,$targetType,$validationMode))
                return false;
        }
    }
    function setToModel($remObject)
    {
        foreach($this->definition["FIELDS"] as $key=>$value)
        {
            $remObject->{"*".$value}->apply($this->types[$key]->getValue(),\lib\model\types\BaseType::VALIDATION_MODE_NONE);
        }

    }

    function setFromType($type,$validationMode=null)
    {
        if($this->nFields!=1)
            throw new BaseModelException(BaseModelException::ERR_INCOMPLETE_KEY,array("model"=>$this->relObject->model->__getObjectName()));
        $this->setFieldFromType($this->fieldKey,$type,$validationMode);
    }

    function setFromTypeValue($typeField,$newVal,$validationMode=null)
    {
        if($typeField->equals($newVal))
           return;

        // Solo establecemos la relacion como dirty, si no es una relacion inversa.
        // Si es una relacion inversa, realmente, no hemos puesto dirty a nada, ya que no estamos asignando un valor
        // a un campo real del objeto.
        if(!$this->relObject->isInverseRelation())
        {
            $typeField->setValue($newVal);
            $this->setDirty();
        }
        else
        {
            // Si se establece una relacion a null, y es una relacion inversa, lo que se debe hacer es eliminar
            // los campos apuntados.
            $typeField->apply($newVal);
        }
        $this->state=ModelBaseRelation::SET;

    }
    function is_set()
    {
       if($this->state==ModelBaseRelation::SET)
           return true;
        if(!$this->state==ModelBaseRelation::DIRTY)
            return false;
        // Si al menos 1 de los campos que define la relacion no esta a nulo, la relacion no es nula.
        foreach($this->types as $key=>$value)
        {
            if($value->is_set())
                return true;
        }
        return false;
    }

    function setFromValue($val,$validationMode=null)
    {
        if($this->nFields==1)
        {
            if(is_array($val))
                $val=$val[$this->fieldKey];

            $relType=$this->types[$this->fieldKey];
            $this->setFromTypeValue($relType,$val,$validationMode);
        }
        else
        {
            if(is_object($val))
            {
                // TODO : Adaptarlo para permitir asignar relaciones.
                throw new \lib\model\BaseModelException(\lib\model\BaseModelException::ERR_INVALID_VALUE);
                return;
            }
            foreach($this->types as $field=>$type)
            {
                if(!isset($val[$field]))
                {
                    throw new BaseModelException(BaseModelException::ERR_INCOMPLETE_KEY,array("model"=>$this->relObject->model->__getObjectName(),"field"=>$field));
                }
                $this->setFromTypeValue($type,$val[$field],$validationMode);

            }
        }
    }

    function set($value,$validationMode=null)
    {

        if($this->relationValues)
        {
            $this->relationValues->reset();
        }
        $this->waitingRemoteSave=false;
        if(is_object($value) && is_subclass_of($value,"\\lib\\model\\BaseModel"))
        {
            $remObjName=\lib\model\ModelService::getModelDescriptor($this->relObject->getRemoteObject());
            if($remObjName->className==$value->__getObjectName())
            {
                $this->setFromModel($value,$validationMode);
            }
            else
            {
                $this->setFromType($value,$validationMode);
            }
        }
        else
            $this->setFromValue($value,$validationMode);
    }

    function cleanState()
    {
        if($this->state==ModelBaseRelation::DIRTY)
            $this->state=ModelBaseRelation::SET;
        $this->waitingRemoteSave=false;
    }

    function isDirty()
    {
       return ($this->state==ModelBaseRelation::DIRTY || $this->waitingRemoteSave);
    }

    function __toString()
	{
        $cad="";
        foreach($this->types as $key=>$value)
        {
            $cad.=$value->getValue();
            reset($this->types);
            return $cad;
        }
	}

    function getQueryConditions(& $q,$serializer)
    {
        $h=0;
        $extra=$this->relObject->getExtraConditions();
        if($extra)
        {
            if(is_array($extra))
            {
                for($k=0;$k<count($extra);$k++)
                {
                    $econditionKeys[]="[%ec".$k."%]";
                    $q["CONDITIONS"]["ec".$k]=$extra[$k];
                }
            }
        }


        foreach($this->types as $key=>$value)
        {
            $curKey="[%".$h."%]";
            $curVal=$serializer->serializeType($key,$value);
            if(\lib\php\ArrayTools::isAssociative($curVal)) {
                $vals = array_values($curVal);
                $curVal=$vals[0];
            }
            $q["CONDITIONS"][]=array("FILTER"=>array("F"=>$this->definition["FIELDS"][$key],"OP"=>"=","V"=>$curVal));
            $h++;
            $conditionKeys[]=$curKey;
        }
        //$q["BASE"].=" WHERE ".$extraConds." AND ".implode(" AND ",$conditionKeys);
    }

    function serialize($serializerType)
    {

       if($this->state==ModelBaseRelation::UN_SET)
       {
           return array();
       }

       $results=array();


       $serializer=$this->relObject->getSerializer();



       foreach($this->types as $key=>$curType)
       {
            if($curType->is_set())
            {
                $data=$serializer->serializeType($key,$curType);

                if(!is_array($data))
                {
                    $results[$key]=$data;
                    continue;
                }
                foreach($data as $key2=>$value2)
                     $results[$key2]=$value2;

            }


        }
        return $results;
    }
    function setDirty()
    {
        $this->state=ModelBaseRelation::DIRTY;
        $this->relObject->setDirty();
    }



}
/**
 * Class RelationValues: Campos obtenidos de una relacion, sea
 * directa o indirecta.
 *
 *
 */

class RelationValues extends \lib\datasource\TableDataSet
{
    protected  $relatedObjects;
    protected  $accessedIndexes=array();
    protected  $relField;
    protected  $loadMode;
    protected  $nResults;
    protected  $isLoaded;
    protected  $nColumns;
    protected $currentIndex;
    protected $isDirty;
    protected $eraseBeforeInsert=false;

    function __construct($relField,$loadMode)
    {
        $this->relField=$relField;
        $this->loadMode=$loadMode;
        $this->isLoaded=false;
        $this->nResults=null;
        $this->currentIndex=0;
        $this->newObjects=array();

    }
    public function load($srcValues,$fromCode=false,$adding=false)
    {
        if($fromCode && !$adding && $this->relField->isInverseRelation())
        {
            // Si se ha establecido desde codigo, y no se esta aniadiendo, hay que marcar que la relacion
            // hay que borrarla al salvar.
            $this->eraseBeforeInsert=true;
        }
        $this->isLoaded=true;
        if ($srcValues !== null && count($srcValues) > 0) {
        if($fromCode==false)
            $this->relatedObjects=$srcValues;
        else
        {
            // Si estamos estableciendo desde codigo, nos pueden pasar
            // diferentes cosas como valor.
            // Pueden ser instancias del objeto remoto, o simples arrays.
            $model = $this->relField->getModel();
            $local = $this->relField->getRelation()->getTypes();
            $fixedValues = [];
            // Si estamos en una relacion inversa, y el modelo no es nuevo,
            // mapeamos ya los campos locales del modelo, al remoto.

            if($this->relField->isInverseRelation()) {
                if (!$model->__isNew()) {
                    $fields=$this->relField->getRelation()->getFields();
                    // En las keys de local, tenemos el campo local..Tenemos que
                    // asignar el remoto, que es $fields[$k]
                    foreach ($local as $k => $v)
                        $fixedValues[$fields[$k]] = $v->getValue();
                }
            }

            // Se van a crear instancias del objeto relacion por cada uno de los $srcValues:
            // Esto solo es necesario si count(srcValues)>0, ya que si no, se quiere solo limpiar la relacion.
            $destRows = null;
            $remoteModelName = $this->relField->getRemoteObject();
            if($adding==false) {
                $this->relatedObjects = [];
                $this->accessedIndexes = [];
            }

                for ($k = 0; $k < count($srcValues); $k++) {
                    if(!is_array($srcValues[$k]) && is_a($srcValues[$k],$remoteModelName))
                    {
                        $curInstance=$srcValues[$k];
                    }
                    else {
                        if(is_array($srcValues[$k]) && \lib\php\ArrayTools::isAssociative($srcValues[$k])) {
                            $curInstance = new $remoteModelName();
                            // Se copian los valores fijos.
                            foreach ($fixedValues as $k1 => $v1)
                                $srcValues[$k][$k1] = $v1;
                            $errorContainer = new \lib\model\ModelFieldErrorContainer();
                            $curInstance->loadFromArray($srcValues[$k], false,!$fromCode, $errorContainer,true);
                            if (!$errorContainer->isOk()) {
                                // Habia un error en esa especificacion.

                                $path=$this->relField->__getFieldPath();
                                $errorContainer->rethrow($path);

                            }
                        }
                        else
                        {
                            // TODO : lanzar error..Que nos han pasado en el array? No era ni instancia, ni asociativo..
                        }
                    }
                    $this->relatedObjects[] = $curInstance;
                    $this->accessedIndexes[count($this->relatedObjects)-1] = 1;
                }
            }
        }
        else
        {
            $this->relatedObjects=[];
        }
        if($fromCode==true)
        {
            $this->isDirty=true;
            $this->relField->setDirty(true);
        }

        $this->currentIndex=0;

        $this->nResults=count($this->relatedObjects);
    }

    public function loadItem($value,$index)
    {
        if($value==null)
            return;
        $this->relatedObjects[$index]=$value;
    }
    // Implementacion de metodos de TableDataSet
    function setIndex($idx)
    {
        $this->currentIndex=$idx;
    }
    function getField($field)
    {
        return $this[$this->currentIndex]->{$field};
    }


    function getColumn($colName)
    {
        $nItems=$this->count();
        for($k=0;$k<$nItems;$k++)
        {
            $results[]=$this[$k]->{$colName};
        }
        return $results;
    }
    function getRow()
    {
        return $this[$this->currentIndex];
    }

    public function offsetExists($offset)
    {
        $nItems=$this->count();
        return $offset<$nItems;
    }

    public function offsetGet( $offset )
    {
        if(!$this->isLoaded) {
            $this->relField->loadRemote();
        }
        $errored=0;
        if(!isset($this->relatedObjects[$offset])) {
            if($this->loadMode=="LAZY") {
                if($this->relField->loadRemote($offset)<=0) {
                    $errored=1;
                }
            }
            else {
                $errored=1;
            }

            if($errored) {
                if($offset==$this->nResults && $this->relField->isInverseRelation()) {
                    $this->isLoaded=true;
                    $newInst=$this->relField->createRemoteInstance();
                    $this->newObjects[]=$newInst;
                    $this->relatedObjects[]=$newInst;
                    $this->nResults++;
                }
                else {
                    $h=11;
                    throw new BaseModelException(BaseModelException::ERR_INVALID_OFFSET,array("model"=>$this->relField->getModel()->__getObjectName(),"field"=>$this->relField->getName(),"offset"=>$offset));
                }
            }

        }
        else {
            $this->isLoaded=true;
        }
        $this->accessedIndexes[$offset]=1;
		return $this->relatedObjects[$offset];
    }

    public function offsetSet( $offset , $value )
    {
       return false;
    }
    public function offsetUnset($offset)
    {
        // Hay que borrar el elemento indicado en el offset
        if($offset> $this->nResults)
        {
            // TODO : Lanzar aqui una excepcion
            return;
        }
        $this->relatedObjects[$offset]->delete();
        // TODO: Reseteamos todo..Esto no es muy eficiente, pero hacerlo de otra forma
        // puede ser peligroso..Hay que tener muchas cosas en cuenta..
        $this->reset();
    }
    public function add($value)
    {
        if(is_array($value))
        {
            if(\lib\php\ArrayTools::isAssociative($value))
                $v=[$value];
            else
                $v=$value;
        }
        else
        {
            $v=[$value];
        }
        // Se llama a load con los valores, aniadiendo (tercer parametro) en vez de sobreescribiendo.
        $this->load($v,true,true);
    }

    function count()
    {
        if($this->relField->getRelation()->is_set())
        {
            if($this->nResults===null)
            {
               $this->nResults=$this->relField->loadCount();
            }
            return $this->nResults;
        }
        else
        {
            if($this->relatedObjects==null)
                return 0;
            $this->nResults=count($this->relatedObjects);
            return $this->nResults;
        }
    }
    function setCount($nItems)
    {
        $this->nResults=$nItems;
    }
    function setLoaded()
    {
        $this->isLoaded=true;
    }
    function emptyRelation()
    {
        $serializer=$this->relField->getModel()->__getSerializer();
        // Obtenemos la expresion a traves de los valores de la relacion
        $conds=[];
        $this->relField->getRelation()->getQueryConditions($conds,$serializer);
        $conds["TABLE"]=$this->relField->getRemoteTable();
        $serializer->deleteByQuery($conds);
        $this->reset();
    }
    function getRelatedObjects()
    {
        return $this->relatedObjects;
    }
    function markAsAccessed($index)
    {
        $this->accessedIndexes[$index]=1;
    }


    public function save()
    {
        // TODO: Si esta relacion impone condiciones sobre el objeto remoto, por ejemplo, inverserelations,
        // las condiciones deben ser copiadas a los objetos modificados.

        // Recogemos los accedidos, antes de que emptyRelation lo resetee todo.
        $accessed=array_keys($this->accessedIndexes);
        $nAccessed=count($accessed);
        $relObjects=$this->relatedObjects;

        $saved = 0;

        if($this->eraseBeforeInsert==true && $this->relField->isInverseRelation())
        {
            // Si se hizo un set de la relacion, (relaciones inversas) primero hay que borrar la
            // relacion existente.
            $this->emptyRelation();
        }


         if($nAccessed>0) {

             // Se guardan todos los accedidos.

             $isInverse = $this->relField->isInverseRelation();
             if ($isInverse) {
                 $def = $this->relField->getDefinition();
                 $relFields = $def["FIELDS"];
                 $parentModel = $this->relField->getModel();
             }
             for ($k = 0; $k < $nAccessed; $k++) {
                 $curObject = $relObjects[$accessed[$k]];

                 if ($curObject->__isNew()) {
                     if ($isInverse) {
                         foreach ($relFields as $key => $value) {
                             $f = $curObject->__getField($value);
                             if (!$f->is_set())
                                 $curObject->{$value} = $parentModel->{$key};

                         }
                     }
                 }

                 $curObject->save(); //$this->relField->getSerializer());
                 $saved++;

             }
             $this->accessedIndexes = array();
             $this->newObjects = array();
             $this->relField->cleanState();

         }

        return $saved;
    }

    public function isDirty()
    {
        if($this->isDirty)
            return true;

         $accessed=array_keys($this->accessedIndexes);
         $nAccessed=count($accessed);
         if($nAccessed==0)
             return false;

         for($k=0;$k<$nAccessed;$k++)
         {
             $curObject=$this->relatedObjects[$accessed[$k]];
             if($curObject->isDirty())
                 return true;
         }
         return false;
    }

    public function reset()
    {
        $this->relatedObjects=array();
        $this->accessedIndexes=array();
        $this->isLoaded=false;
        $this->currentIndex=0;
        $this->isDirty=false;
        $this->nResults=null;

    }

    public function isLoaded()
    {
        return $this->isLoaded;
    }
    public function countColumns()
    {
        // Se obtienen los datos a partir de la definicion del objeto
        if($this->nColumns!==null)
            return $this->nColumns;

        if($this->count()>0)
        {
            $obj=$this[0];
            $this->nColumns=$obj->getFieldCount();
            return $this->nColumns;

        }
        return 0;
    }

    public function getMetaData()
    {
        if($this->count()>0)
        {
            $obj=$this[0];
            return $obj->getDefinition();
        }
        return null;
    }
}