<?php
namespace model\ads\Demo\actions;

/**
 * FILENAME:/var/www/adtopy/model/ads/objects/Demo/actions/EditAction.php
 * CLASS:EditAction
 */
class Edit extends \lib\action\Action
{
    
    static $definition = array(
        'MODEL' => '\model\ads\Demo',
        'ROLE' => 'Edit',
        'PERMISSIONS' => array(
        //             array(
            //                 'MODEL' => '\model\ads\Demo',
            //                 'PERMISSION' => 'edit'
            //             )
        ),
        'IS_ADMIN' => false,
        'INDEXFIELDS' => array(
            "id"
        ),
        'FIELDS' => array(
            'id' => array(
                'REQUIRED' => 1,
                'FIELD' => 'id',
                'MODEL' => '\model\ads\Demo'
            ),
            'domain' => array(
                'REQUIRED' => 1,
                'FIELD' => 'domain',
                'MODEL' => '\model\ads\Demo'
            ),
            'config' => array(
                'REQUIRED' => 0,
                'FIELD' => 'config',
                'MODEL' => '\model\ads\Demo'
            )
        )
    );
    
    /**
     * NAME:__construct
     *
     * DESCRIPTION: Constructor for EditAction
     *
     * PARAMS:
     *
     * RETURNS:
     */
    function __construct()
    {
        parent::__construct(Edit::$definition);
    }
    
    /**
     * NAME:validate
     *
     * DESCRIPTION: Callback for validation of action :EditAction
     *
     * PARAMS:
     *
     * $params: Parameters received,as a BaseTypedObject.
     * Its fields are:
     * fields: tag,id_site,name,date_add,date_modified,id_type,isPrivate,path,title,tags,description
     *
     * $actionResult:\lib\action\ActionResult instance.Errors found while validating this action must be notified to this object
     *
     * $user: User executing this request
     *
     * RETURNS:
     */
    function validate($actionResult)
    {
        
        /* Insert the validation code here */
        return $actionResult->isOk();
    }
    
    /**
     * NAME:onSuccess
     *
     * DESCRIPTION: Callback executed when this action had success.EditAction
     *
     * PARAMS:
     *
     * $model: If this object had a related model, it'll be received in this parameter, once it has been saved.
     *
     * $user: User executing this request
     *
     * RETURNS:
     */
    function onSuccess($model, $user)
    {
        
        /* Insert callback code here */
        return true;
    }
    
    /**
     * NAME:onError
     *
     * DESCRIPTION: Callback executed when this action had an errorEditAction
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
    function onError($keys, $params, $actionResult, $user)
    {
        
        /* Insert callback code here */
        return true;
    }
}
?>
