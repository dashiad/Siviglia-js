<?php
/**
 * Created by PhpStorm.
 * User: JoseMaria
 * Date: 05/12/2017
 * Time: 0:22
 */

namespace sites\backend\pages\JobDetail;

use lib\model\ModelService;
use lib\model\ModelDescriptor;

class JobDetailPage extends  \model\web\Page
{
    
    const BASE_CLASS = '\model\web\Jobs\Worker'; // clase desde la que se cargan los datos por id
    const EXTRA_PATH = 'workers'; // ruta dentro del directorio de widgets del objeto
   
    function initializePage($params)
    {
        // Carga el job y extrae el paquete del tipo de worker a partir del id recibido
        $s=\Registry::getService("model");
        $ins=$s->loadModel(self::BASE_CLASS, ["job_id" => $this->job_id]);
        $package = ModelService::getPackage($ins->worker_type);
        
        // identifica el modelo y el datasource a partir de los parámetros del job
        $jobData = json_decode($ins->descriptor, true)['params']['params'];
        $modelName = $jobData['model'];
        $dsName = $jobData['datasource'];
                
        // pasa al template la ruta del widget, obtenida a partir de los datos anteriores 
        $md = new ModelDescriptor($modelName, null, $package);
        $resultJobWidget = $md->getWidgetPath($dsName, self::EXTRA_PATH);      
        $this->setTemplateParams([
            "JobDetailWidget" => $resultJobWidget,
        ]);
    }
}
                                                                                                                                                                