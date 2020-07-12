<?php
namespace model\ads\SmartConfig\html\forms;
/**
 FILENAME:/var/www/adtopy/model/web/objects/Site//html/forms/EditAction.php
  CLASS:EditAction
*
*
**/

class Edit extends \lib\output\html\Form
{
    static  $definition=array(
        'NAME'=>'Edit',
        'MODEL'=>'\model\ads\SmartConfig',
        'ACTION'=>array(
            'MODEL'=>'\model\ads\SmartConfig',
            'ACTION'=>'Edit',
            'INHERIT'=>1
        ),
        'ROLE'=>'Edit',
        'FIELDS' => [
            'domain' => ['MODEL' => '\model\ads\SmartConfig', 'FIELD' => 'domain'],
            'config' => ['MODEL' => '\model\ads\SmartConfig', 'FIELD' => 'config'],
        ],
        'REDIRECT'=>array(
            'ON_SUCCESS'=>'',
            'ON_ERROR'=>''
        ),
        'INPUTS'=>array(
            'domain' => [
                'TYPE' => 'String',
                'PARAMS' => [],
            ],
            'config' => [
                'TYPE' => 'String',
                'PARAMS' => [],
            ],        
        ),
        'INDEXFIELDS'=>array(
             'domain'=>array(
                'REQUIRED'=>1,
                'FIELD'=>'domain',
                'MODEL'=>'\model\ads\SmartConfig'
            )
        ),
        'GROUPS' => [
//             'TAB1' => [
//                 'FIELDS' => ['/config/*'],
//                 'LABEL' => 'Configuración',
//             ],
        ],
        'INPUTPARAMS' => [
            '/' => ['INPUT'=>'FlexContainer'],
            '/config' => ['INPUT'=>'ActionList'],
            'Exelate' => ['INPUT'=>'GridContainer'],
//             '/config/*' => [
//                 'LABEL' => 'Configuración',
//                 'INPUT'=>'TabsContainer'
//             ],
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
