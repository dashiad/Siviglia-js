<?php
/**
 * Created by PhpStorm.
 * User: JoseMaria
 * Date: 05/12/2017
 * Time: 0:22
 */

namespace sites\editor\pages\Job;

include_once(PROJECTPATH."/model/web/objects/Jobs/objects/Worker/Definition.php");

class JobPage extends \model\web\Jobs\Worker\Definition
{
    function initializePage($params)
    {
        die("ok");
    }
    function getFormModel($model,$form)
    {
        $s=\Registry::getService("model");
        //$ins=$s->loadModel('\model\web\Job',["id_job"=>$this->id_job]);
        $ins=$s->loadModel('\model\web\Jobs\Worker',["job_id"=>$this->job_id]);
        return $ins;
    }
}
