<?php
namespace model\ads\SmartConfig\serializers;

require_once(__DIR__."/SmartConfigSerializer.php");

//use \lib\model\BaseTypedObject;
use \lib\datasource\TableDataSource;
use lib\datasource\DataSource;
use model\ads\SmartConfig\serializers\storage\QueryBuilder;
//use model\ads\SmartConfig\serializers\SmartConfig\storage\SmartConfig;

class SmartConfigDataSource extends TableDataSource {
    
    protected $definition;
    protected $serializerDefinition;
    protected $serializer;
    
    protected $data;
    protected $numRows;
    protected $matchingRows;
    
    public function __construct($objName, $dsName, $definition)
    {
        parent::__construct($objName, $dsName, $definition::$definition);
        $this->definition=$definition;
        $this->serializerDefinition = $definition::$definition["SOURCE"]["STORAGE"]["smartconfig"]["DEFINITION"];
        $this->serializer = new SmartConfigSerializer($this->serializerDefinition);
    }
    
    public function getIterator($rowInfo = null)
    {}

    public function fetchAll()
    {
        $fields = array_keys($this->__fieldDef);
        $params = [];
        
        foreach ($fields as $field) {
            $params[$field] = $this->__getField($field)->getValue();
        }
        
        return $this->serializer->fetchAll($this->serializerDefinition, $this->data, $this->numRows, $this->matchingRows, $params, null);
    }

    public function count()
    {
        return $this->numRows;
    }

    public function getMetaData()
    {
        //
    }

    public function countColumns()
    {
        return count(array_keys($this->__fieldDef));
    }

    public function getStartingRow() {
        return 0;
    }
    
    
}