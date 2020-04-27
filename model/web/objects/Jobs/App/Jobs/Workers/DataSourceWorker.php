<?php
namespace model\web\Jobs\App\Jobs\Workers;

class DataSourceWorker extends Worker
{
    protected $name = "ds_worker";
    
    protected $datasourceModel;
    protected $datasourceName;
    
    protected function init()
    {
        //
    }
    
    protected function runItem($item)
    {   
        $model = $item['model'];
        $dsName = $item['datasource'];
    
        $serializer = null;
        $ds = \getDataSource($model, $dsName, $serializer);
        $this->loadFields($ds, $item);
        
        $result = $ds->fetchAll();
        return $result->getFullData();
    }
    
    protected function loadFields(&$ds, $item) 
    {
        // permitir usar ROLE dentro de PARAMS para especificar la clave
        $params = array_keys($ds->getDefinition()['PARAMS']);
        foreach ($params as $key) {
            if (isset($item[$key]))
                $ds->{$key} = $item[$key];
        }
    }

}
