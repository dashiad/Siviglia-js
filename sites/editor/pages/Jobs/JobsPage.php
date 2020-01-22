<?php
/**
 * Created by PhpStorm.
 * User: JoseMaria
 * Date: 05/12/2017
 * Time: 0:22
 */

namespace sites\editor\pages\Jobs;


class JobsPage extends \model\web\Job
{
    function initializePage($params)
    {
    }
    function getFormModel($model,$form)
    {
        $s=\Registry::getService("model");
        $ins=$s->loadModel('\model\web\Job',["id_job"=>$this->id_job]);
        return $ins;
    }
}
