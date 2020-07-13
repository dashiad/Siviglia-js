<?php
//namespace lib\datasource;
namespace  model\ads\Comscore\serializers;

require_once(PROJECTPATH."lib/datasource/TableDataSet.php");

use \lib\datasource\TableDataSource;
use \lib\datasource\TableDataSet;
//use \lib\datasource\CsvDataSource;

class CsvDataSource extends TableDataSource
{
    protected $numRows      = null;
    protected $numColumns   = null;
    protected $data = null;
    protected $hasHeaderRow = false;
    protected $readFromFile = true;
    protected $columnNames;
    
    
    protected $iterator;
    protected $metadata = null;
    
    protected $DSConfig = [];
    
    public function __construct($objName, $dsName, $definition)
    {
        parent::__construct($objName, $dsName, $definition);
        $this->columnNames = array_keys($this->__returnedFields);
        $this->numColumns = count($this->columnNames);
        $this->DSConfig = $this->__objectDef['SOURCE']['CSV'];
        //$this->hasHeaderRow = $this->DSConfig['HAS_HEADER_ROW'] ?? false;
        //$this->readFromFile = ($this->DSConfig['READ_FROM']=='FILE');        
    }
    
    public function fetchAll()
    {
        if ($this->readFromFile)
            $this->numRows = $this->readFromFile();
        else
            $this->numRows = $this->readFromString();
        
        return $this->getIterator();
    }
    
    protected function readFromFile()
    {
        if (empty($this->filename)) throw new DataSourceException(DataSourceException::ERR_PARAM_REQUIRED, "filename");
        $file = fopen($this->filename, "r");
        if ($file===false) throw new DataSourceException(DataSourceException::ERR_NO_SUCH_DATASOURCE);
        
        try {
            $this->data = [];
            $skipRow = $this->hasHeaderRow;
            while(!feof($file)) {
                $row =  fgetCsv($file);
                if (!$skipRow) {
                    if ($row!==false) $this->data[] = $row;
                } else {
                    $skipRow = false;
                }
            }
        } finally {
            fclose($file);
        }
        return count($this->data);
    }
    
    protected function readFromString()
    {
        if (empty($this->csv)) throw new DataSourceException(DataSourceException::ERR_PARAM_REQUIRED, "csv");
        $rawData = str_getcsv($this->csv);
        if ($this->hasHeaderRow) 
            array_shift($rawData);
        $this->data = $rawData;
        return count($this->data);
    }
    
    function getIterator($rowInfo=null)
    {
        if( !$this->iterator )
        {
            $this->iterator= new CsvDataSet($this, $this->data, $this->columnNames, $this->numRows);
        }
        return $this->iterator;
    }

    public function count()
    {
        return $this->numRows; // TODO: contar filas de fichero o data si no se ha llamado a fetchall?
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

class CsvDataSet extends TableDataSet
{
    
    var $parentDs;
    var $data;
    var $currentIndex=0;
    
    function __construct($parentDs, $data, $columns, $count)
    {
        $this->parentDs=$parentDs;
        $this->invColumns=array_flip($columns);
        $this->data=$data;
        $this->columns=$columns;
        $this->count=$count;
        $this->rowSet=new CsvTableRowDataSet($this);
    }
    
    
    function setIndex($index)
    {
        $this->currentIndex=$index;
        
    }
    
    function count()
    {
        return $this->count;
    }
    
    function getRow()
    {
        return $this->data[$this->currentIndex];
    }
    
    function getField($varName)
    {
        $val=$this->data[$this->currentIndex][$this->invColumns[$varName]];
        return $val;
    }
    
    function getColumn($col)
    {
        for( $k=0;$k<$this->count;$k++)
        {
            $results[]=$this->data[$k][$this->invColumns[$col]];
        }
        return $results;
    }
    
    function offsetExists($index)
    {
        
        return $index < $this->count;
    }
    
    function offsetGet($index)
    {
        $this->currentIndex=$index;
        return $this->rowSet;
    }
    
    function offsetSet($index,$newVal)
    {
    }
    
    function offsetUnset($index)
    {
        
    }
    
    function __get($varName)
    {
        $this->currentIndex=0;
        return $this->getField($varName);
    }

    function getFullData()
    {
        $data = $this->data;
        return $data;
    }
    
}

class CsvTableRowDataSet {
    var $tableDataSet;
    
    function __construct($tableDataSet)
    {
        $this->tableDataSet = $tableDataSet;
    }
    
    function __get($varName)
    {
        return $this->tableDataSet->getField($varName);
    }
}
