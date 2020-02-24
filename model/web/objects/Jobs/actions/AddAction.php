<?php
namespace model\web\Jobs\actions;
use model\web\Jobs\App\Jobs\JobManager;

/**
 FILENAME:/var/www/adtopy/model/web/objects/Jobs/actions/AddAction.php
 CLASS:AddAction
 *
 *
 **/

class AddAction extends \lib\action\Action
{
    static  $definition = [
        //'MODEL' => '\model\web\Jobs', // no se asocia a un modelo
        'ROLE'  => 'Add',
        //'ROLE'  => 'Static', // usar y cargar con onSuccess
        'PERMISSIONS' => [
            [
                'MODEL'      => '\model\web\Jobs',
                'PERMISSION' => 'create'
            ],
        ],
        'IS_ADMIN' => false,
        'FIELDS' => [
            'job_id'  => [
                'REQUIRED' => 0,
                'FIELD'    => 'job_id',
                'MODEL'    => '\model\web\Jobs'
            ],
            'name' => [
                'REQUIRED'   => 1,
                'FIELD'      => 'name',
                'MODEL'      => '\model\web\Jobs',
            ],
            'descriptor' => [
                'REQUIRED'   => 1,
                'FIELD'      => 'descriptor',
                'MODEL'      => '\model\web\Jobs',
            ],
            'parent' => [
                'REQUIRED'   => 0,
                'FIELD'      => 'parent',
                'MODEL'      => '\model\web\Jobs',
            ],
        ],
    ];
    
    
    /**
     *
     * NAME:__construct
     *
     * DESCRIPTION: Constructor for AddAction
     *
     * PARAMS:
     *
     * RETURNS:
     */
    function __construct( )
    {
        parent::__construct(AddAction::$definition);       
    }
    
    
    
    /**
     *
     * NAME:validate
     *
     * DESCRIPTION: Callback for validation of action :AddAction
     *
     * PARAMS:
     *
     * $params: Parameters received,as a BaseTypedObject.
     *		 Its fields are:
     *		 fields: tag,id_site,name,date_add,date_modified,id_type,isPrivate,path,title,tags,description
     *
     * $actionResult:\lib\action\ActionResult instance.Errors found while validating this action must be notified to this object
     *
     * $user: User executing this request
     *
     * RETURNS:
     */
    function validate ( $actionResult )
    {
        /* Insert the validation code here */
        return $actionResult->isOk();
    }
    
    function onSaved($model) 
    {
        $jobId = JobManager::createJob(json_decode($model->descriptor, true));
        $realModel = new \model\web\Jobs;
        $realModel->job_id = $jobId;
        $realModel->name = $model->name;
        $realModel->descriptor = $model->descriptor;
        $realModel->parent = $model->parent;
        //$realModel->loadFromFields(); // no sabemos si está en bd
        $this->setModel($realModel);
    }
    
    
    /**
     *
     * NAME:onSuccess
     *
     * DESCRIPTION: Callback executed when this action had success.AddAction
     *
     * PARAMS:
     *
     * $model: If this object had a related model, it'll be received in this parameter, once it has been saved.
     *
     * $user: User executing this request
     *
     * RETURNS:
     */
    function onSuccess( $model, $user)
    {
        /* Insert callback code here */
        return true;        
    }
    
    
    
    /**
     *
     * NAME:onError
     *
     * DESCRIPTION: Callback executed when this action had an errorAddAction
     *
     * PARAMS:
     *
     * $keys: Keys received
     *
     * $params: Parameters received.Note these parameters are the same received in Validate
     *
     * $actionResult:\lib\action\ActionResult instance.Errors found while validating this action must be notified to this object
     *
     * $user: User executing this request
     *
     * RETURNS:
     */
    function onError( $keys, $params, $actionResult, $user)
    {
        /* Insert callback code here */
        return true;       
    }
    
}