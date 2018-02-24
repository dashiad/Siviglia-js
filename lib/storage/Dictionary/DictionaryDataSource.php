<?php
namespace lib\storage\Dictionary;

class DictionaryDataSource extends \lib\datasource\ArrayDataSource
{
    protected $serializer;
    protected $data;
    protected $nRows;
    protected $iterator;
    protected $parameters;
    protected $serializerDefinition;
    protected $definitionInstance;
    function __construct($objName,$dsName,$definitionInstance,$serializer,$serializerDefinition=null)
    {
        parent::__construct($objName, $dsName, $definitionInstance::$definition);

        if($serializerDefinition)
            $this->serializerDefinition=$serializerDefinition;
        if(!$serializer)
            $serializer=new DictionarySerializer($serializerDefinition,"PHP");
        $this->definitionInstance=$definitionInstance;
        $this->serializer=$serializer;
    }

    function setParameters($obj)
    {
        $remFields=$obj->__getFields();
        if(!$remFields)
            return;

        foreach($remFields as $key=>$value) {
            $types=$value->getTypes();
            foreach($types as $tKey=>$tValue) {
                $this->parameters[$tKey] = $tValue->getValue();
            }
        }
    }

    function getPagingParameters()
    {
        //Do not use pagingParameters, if needed use it in the calling method as normal parameters
    }

    function fetchAll()
    {
        $def = $this->getOriginalDefinition();
        $definition = $def['STORAGE']['DICTIONARY']['DEFINITION'];

        if (!isset($definition['METHOD'])) {
            throw new \lib\datasource\DataSourceException(\lib\datasource\DataSourceException::ERR_NO_MODEL_OR_METHOD);
        }
        $method = $definition['METHOD'];
        $params = $this->parameters;
        if(isset($definition["MODEL"]) && $definition["MODEL"]!="self") {
            $mdl = \getModel($definition['MODEL']);
        }
        else
        {
            $mdl=$this->definitionInstance;
        }
        $this->data = $mdl->{$method}($this);
        if($this->data) {
            $this->nRows = count($this->data);
        }
        else
        {
            $this->data=array();
            $this->nRows=0;
        }

        $this->iterator=new \lib\model\types\DataSet(array("FIELDS"=>$def['FIELDS']),$this->data,$this->nRows,$this->nRows,$this,array());

        return $this->iterator;
    }

    function count()
    {
        return $this->nRows;
    }

    function getStartingRow()
    {
        return 0;
    }
    function getSerializer()
    {
        return $this->serializer;
    }
}
