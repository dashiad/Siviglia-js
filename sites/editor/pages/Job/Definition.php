<?php
/**
 * Created by PhpStorm.
 * User: JoseMaria
 * Date: 19/11/2017
 * Time: 1:22
 */

namespace sites\editor\pages\Job;

include_once(PROJECTPATH."/model/web/objects/Job/JobDefinition.php");
class Definition extends \model\web\Job\JobDefinition
{
    function getJobDefinition()
    {
        return array(
            'CACHING'=>array('TYPE'=>'NO-CACHE'),
            'ENCODING'=>'utf8',
            'PERMISSIONS'=>array('PUBLIC'),
            'SOURCES'=>array(
            ),
            'FIELDS'=>array(
                'id_job'=>[
                    "MODEL"=>"/model/web/Job",
                    "FIELD"=>"id_job"
                ]
            )
        );
    }
}
