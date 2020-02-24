<?php
namespace lib\datasource;

class MethodDataSource extends \lib\datasource\ArrayDataSource
{
    protected $serializer;
    protected $data;
    protected $nRows;
    protected $iterator;
    protected $parameters;

    function __construct($objName,$dsName,$definition,$serializer,$serializerDefinition=null)
    {
        parent::__construct($objName, $dsName, $definition);

        if($serializerDefinition)
            $this->serializerDefinition=$serializerDefinition;
        $this->serializer=$serializer;
    }

   /* function setParameters($obj)
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
    }*/

    function getPagingParameters()
    {
        //Do not use pagingParameters, if needed use it in the calling method as normal parameters
    }

    function fetchAll()
    {
        $def = $this->getOriginalDefinition();
        $definition = $def['SOURCE']['METHOD']['DEFINITION'];

        if (!isset($definition['MODEL']) || !isset($definition['METHOD'])) {
            throw new \lib\datasource\DataSourceException(\lib\datasource\DataSourceException::ERR_NO_MODEL_OR_METHOD);
        }

        $model=$definition["MODEL"];
        if($model=="self")
        {
            $objNameClass=\lib\model\ModelService::getModelDescriptor($this->objName);
            require_once($objNameClass->getDataSourceFileName($this->dsName));
            $objName=$objNameClass->getNamespaced();
            $csN=$objName.'\datasources\\'.$this->dsName;
            $mdl=new $csN();
        }
        else
            $mdl = \getModel($definition['MODEL']);
        $method = $definition['METHOD'];
        $params = $this;
        $data = $mdl->{$method}($params);
        for($k=0;$k<count($data);$k++)
        {
            foreach($def['FIELDS'] as $fieldName=>$fieldDef) {
                $this->data[$k][$fieldName] = $data[$k][$fieldName];
            }
        }
        $this->nRows = count($this->data);

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
}
