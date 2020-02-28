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

include_once(PROJECTPATH."/model/web/objects/Jobs/objects/Worker/Definition.php");

class JobDetailPage extends  \model\web\Page
{
    protected $className = '\model\web\Jobs\Worker';
    
    function initializePage($params)
    {
        $s=\Registry::getService("model");
        $ins=$s->loadModel($this->className, ["job_id"=>$this->job_id]);
        $package = ModelService::getPackage($ins->worker_type);
        
        $md = new ModelDescriptor($ins->worker_type, "", $package);
        $resultJobWidget = $md->getWidgetPath(); // por defecto carga JOB_DEFAULT del worker
        //$resultJobWidget = $md->getWidgetPath("JOB_ESPECIAL"); // plantilla por nombre
        
        $this->setTemplateParams([
            "JobWidget" => $resultJobWidget,
        ]);
    }
    
    function getFormModel($model,$form)
    {        
        $s=\Registry::getService("model");
        $ins=$s->loadModel('\model\web\Job',["id_job"=>$this->id_job]);
        //$ins=$s->loadModel($this->className, ["job_id"=>$this->job_id]);
        return $ins;
    }
}
                                                                                                                                                                