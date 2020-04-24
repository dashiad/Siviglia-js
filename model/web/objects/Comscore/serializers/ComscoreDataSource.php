<?php
namespace model\web\Comscore\serializers;

require_once(__DIR__."/CsvDataSource.php");

//use \lib\datasource\CsvDataSource;
use \lib\model\BaseTypedObject;
use model\web\Comscore\serializers\Comscore\storage\Comscore;

class ComscoreDataSource extends CsvDataSource
{
    protected $numRows      = null;
    protected $numColumns   = null;
    protected $data = null;
    protected $hasHeaderRow = true;
    protected $readFromFile = true;
    protected $columnNames;   
    
    protected $iterator;
    protected $metadata = null;
    
    protected $DSConfig = [];
    protected $action;
    protected $type;
    protected $filename;
    protected $params = [];
    
    const POLL_PAUSE = 10;
    const BASE_DIR = '/vagrant/data/csv/'; // TODO: definir en configuración ruta base para los archivos
    
    public function __construct($objName, $dsName, $definition)
    {       
        parent::__construct($objName, $dsName, $definition::$definition);
        $this->DSConfig = $this->__objectDef['SOURCE']['STORAGE']['COMSCORE'];
        $this->columnNames = array_keys($this->__returnedFields);
        $this->numColumns = count($this->columnNames);
        
        $this->action = $this->DSConfig['ACTION'];
        $this->type = $this->DSConfig['TYPE'];        
        
    }
    
    public function fetchAll()
    {

        /**
         * 
         * @var BaseTypedObject $model
         */
        $model = \getModel("model\web\Comscore");
        
        $this->action = "requestReport";
        $this->params = [
            'action' => $this->action,
            'type' => $this->type,
            'params' => [
                'region' => "spain", // $this->region, // TODO: coger etiqueta desde valor numérico del enum
                'start_date' => $this->start_date,
                'end_date' => $this->end_date,
                'campaigns' => $this->campaigns,
            ],
        ];
        
        $st = new Comscore($model->getDefinition());
        $comscoreJob = json_decode($st->request($this->params));
        
        if (isset($comscoreJob->error)) {
            $comscoreJobId = $comscoreJob->error->details[0]->conflictedRecordId;
        } else {
            $comscoreJobId = $comscoreJob->data->id;
        }
        
        $dataReady = false;
        
        while(!$dataReady) {
            $this->action = "checkReport";
            $this->params = [
                'action' => $this->action,
                'params' => [
                    'region' => "spain", // $this->region, // TODO: coger etiqueta desde valor numérico del enum
                    'report_id' => $comscoreJobId,
                ],
            ];
            $comscoreJobStatus = (json_decode($st->request($this->params))->data->status=="COMPLETED");
            $dataReady = $comscoreJobStatus;
            if (!$dataReady) sleep(static::POLL_PAUSE);
        }
        
        $this->action = "getReport";
        $this->params = [
            'action' => $this->action,
            'params' => [
                'region' => "spain", // $this->region, // TODO: coger etiqueta desde valor numérico del enum
                'report_id' => $comscoreJobId,
            ],
        ];
        $report = $st->request($this->params);
        
        $this->filename = static::BASE_DIR."comscore_report_".time().".csv";
        $file = fopen($this->filename, "w+");
        fwrite($file, $report);
        fclose($file);
                
        parent::fetchAll();
        
        return $this->getIterator();

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
