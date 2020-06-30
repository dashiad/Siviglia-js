<?php
namespace model\ads\Demo\html\forms;
/**
 FILENAME:/var/www/adtopy/model/web/objects/Demo//html/forms/EditAction.php
 CLASS:EditAction
 *
 *
 **/

class Edit extends \lib\output\html\Form
{
    static  $definition=array(
        'NAME'=>'Edit',
        'MODEL'=>'\model\ads\Demo',
        'ACTION'=>array(
            'MODEL'=>'\model\ads\Demo',
            'ACTION'=>'Edit',
            'INHERIT'=>1
        ),
        'FIELDS' => [
            'id' => ['MODEL' => '\model\ads\Demo', 'FIELD' => 'id'],
            'domain' => ['MODEL' => '\model\ads\Demo', 'FIELD' => 'domain'],
            'config' => ['MODEL' => '\model\ads\Demo', 'FIELD' => 'config'],
        ],
        'ROLE'=>'Edit',
        'REDIRECT'=>array(
            'ON_SUCCESS'=>'',
            'ON_ERROR'=>''
        ),
        'INPUTS'=>array(
            'id' => ['TYPE' => 'String', 'PARAMS' => []],
            'domain' => ['TYPE' => 'String', 'PARAMS' => []],
            'config' => ['TYPE' => 'String', 'PARAMS' => []],        
        ),
        'INDEXFIELDS'=>array(
             'id'=>array(
                    'REQUIRED'=>1,
                    'FIELD'=>'id',
                    'MODEL'=>'\model\ads\Demo'
                )
        ),
//         'GROUPS' => [
//             'TAB1' => [
//                 'LABEL'=>'TAB1', 'FIELDS'=>['/config/.*/Exelate',]
//             ],
//             'TAB2' => [
//                 'LABEL'=>'TAB1', 'FIELDS'=>['/config/.*/AdnSegments']
//             ],
//         ],
        'INPUTPARAMS' => [
//             '/' => ['INPUT'=>'TabsContainer'],
            '/' => ['INPUT'=>'FlexContainer'],
        ],
    );
    
    
    /**
     *
     * NAME:__construct
     *
     * DESCRIPTION: Constructor for EditAction
     *
     * PARAMS:
     *
     * $actionResult:\lib\action\ActionResult instance.Errors found while validating this action must be notified to this object
     *
     * RETURNS:
     */
    function __construct( $actionResult=null)
    {
        
        parent::__construct(Edit::$definition,$actionResult);
        
    }
    
    
    
    /**
     *
     * NAME:onSuccess
     *
     * DESCRIPTION: Callback executed when this form had success.EditAction
     *
     * PARAMS:
     *
     * $actionResult: Action Result object
     *
     * RETURNS:
     */
    function onSuccess( $actionResult)
    {
        
        
        /* Insert callback code here */
        
        return true;
        
    }
    
    
    
    /**
     *
     * NAME:onError
     *
     * DESCRIPTION: Callback executed when this action had an errorEditAction
     *
     * PARAMS:
     *
     * $actionResult:\lib\action\ActionResult instance.Errors found while validating this action must be notified to this object
     *
     * RETURNS:
     */
    function onError( $actionResult)
    {
        
        
        /* Insert callback code here */
        
        return true;
        
    }
    
}
?>
