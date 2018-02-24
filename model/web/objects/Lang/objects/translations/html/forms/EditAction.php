<?php
namespace model\web\Lang\translations\html\forms;
/**
 FILENAME:/var/www/percentil/backoffice//backoffice/objects/Lang/objects/translations//html/forms/EditAction.php
  CLASS:EditAction
*
*
**/

class EditAction extends \lib\output\html\Form
{
	 static  $definition=array(
               'NAME'=>'EditAction',
               'OBJECT'=>'Lang\translations',
               'ACTION'=>array(
                     'OBJECT'=>'\model\web\Lang\translations',
                     'ACTION'=>'EditAction',
                     'INHERIT'=>1
                     ),
               'ROLE'=>'Edit',
               'REDIRECT'=>array(
                     'ON_SUCCESS'=>'',
                     'ON_ERROR'=>''
                     ),
               'INPUTS'=>array(),
               'INDEXFIELDS'=>array(
                     'id_translation'=>array(
                           'REQUIRED'=>1,
                           'FIELD'=>'id_translation',
                           'MODEL'=>'\model\web\Lang\translations'
                           )
                     )
               );


	/**
	 *
	 * NAME:__construct
	 *
	 * DESCRIPTION: Constructor for EditAction
	 *
	 * PARAMS:
	 *
	 * $actionResult:\lib\action\ActionResult instance.Errors found while validating this action must be notified to this object	 *
	 * RETURNS:
	 */
	function __construct( $actionResult=null)
	{

			parent::__construct(EditAction::$definition,$actionResult);
	
	}



	/**
	 *
	 * NAME:validate
	 *
	 * DESCRIPTION: Callback for validation of form :EditAction
	 *
	 * PARAMS:
	 *
	 * $params: Parameters received,as a BaseTypedObject.
	 *		 Its fields are:
	 *		 	 *
	 * $actionResult:\lib\action\ActionResult instance.Errors found while validating this action must be notified to this object	 *
	 * $user: User executing this request	 *
	 * RETURNS:
	 */
	function validate ( $actionResult )
	{


	/* Insert the validation code here */
	
			return $actionResult->isOk();
	
	}



	/**
	 *
	 * NAME:onSuccess
	 *
	 * DESCRIPTION: Callback executed when this form had success.EditAction
	 *
	 * PARAMS:
	 *
	 * $actionResult: Action Result object	 *
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
	 * $actionResult:\lib\action\ActionResult instance.Errors found while validating this action must be notified to this object	 *
	 * RETURNS:
	 */
	function onError( $actionResult)
	{


	/* Insert callback code here */
	
	return true;
	
	}

}
?>