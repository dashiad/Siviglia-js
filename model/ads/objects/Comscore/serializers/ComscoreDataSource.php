<?php
namespace model\ads\Comscore\serializers;

require_once(__DIR__."/CsvDataSource.php");
require_once(__DIR__."/ComscoreSerializer.php");

use \lib\model\BaseTypedObject;
use model\ads\Comscore\serializers\Comscore\storage\Comscore;
use lib\storage\Comscore\ComscoreSerializer;

class ComscoreDataSource extends CsvDataSource
{
    protected $numRows      = null;
    protected $numColumns   = null;
    protected $matchingRows;
    protected $data = null;
    protected $hasHeaderRow = true;
    protected $readFromFile = true;
    protected $columnNames;   
    
    protected $serializer;
    protected $iterator;
    protected $metadata = null;
    
    protected $DSConfig = [];
    protected $action;
    protected $type;
    protected $filename;
    protected $params = [];
    protected $definition;
    protected $serializerDefinition;
    
    public function __construct($objName, $dsName, $definition)
    {   
        parent::__construct($objName, $dsName, $definition::$definition);
        $this->definition=$definition;
        $this->serializerDefinition = $definition::$definition["SOURCE"]["STORAGE"]["comscore"]["DEFINITION"];
        $this->serializer = new \model\ads\Comscore\serializers\ComscoreSerializer($this->serializerDefinition);
    }
    
    public function fetchAll()
    {

        /**
         * 
         * @var \lib\model\BaseTypedObject $model
         */
        $model = \getModel("model\ads\Comscore");
        $params = [
            'region' => $this->region,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'campaigns' => json_encode($this->campaigns),
        ];
        // REVISAR DATASET DEVUELTO
        return $this->serializer->fetchAll($this->serializerDefinition, $this->data, $this->numRows, $this->matchingRows, $params, null);
    }
    
       
    public function getFilename() : ?String
    {
        return $this->filename;
    }
    
    public function getIterator($rowInfo = null)
    {
       return parent::getIterator($rowInfo);
    }

    public function count()
    {
        return $this->numRows;
    }

    public function getMetaData()
    {
        return $this->metadata;
    }

    public function countColumns()
    {
        return $this->numColumns;
    }

    
}
