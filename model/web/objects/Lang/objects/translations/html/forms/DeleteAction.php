<?php
namespace model\web\Lang\translations\html\forms;
/**
 FILENAME:/var/www/percentil/backoffice//backoffice/objects/Lang/objects/translations//html/forms/DeleteAction.php
  CLASS:DeleteAction
*
*
**/

class DeleteAction extends \lib\output\html\Form
{
	 static  $definition=array(
               'NAME'=>'DeleteAction',
               'OBJECT'=>'Lang\translations',
               'ACTION'=>array(
                     'OBJECT'=>'\model\web\Lang\translations',
                     'ACTION'=>'DeleteAction',
                     'INHERIT'=>1
                     ),
               'ROLE'=>'Delete',
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
                     ),
               'NOFORM'=>1
               );


	/**
	 *
	 * NAME:__construct
	 *
	 * DESCRIPTION: Constructor for DeleteAction
	 *
	 * PARAMS:
	 *
	 * $actionResult:\lib\action\ActionResult instance.Errors found while validating this action must be notified to this object	 *
	 * RETURNS:
	 */
	function __construct( $actionResult=null)
	{

			parent::__construct(DeleteAction::$definition,$actionResult);
	
	}



	/**
	 *
	 * NAME:validate
	 *
	 * DESCRIPTION: Callback for validation of form :DeleteAction
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
	function validateAction ( $actionResult )
	{


	/* Insert the validation code here */
	
			return $actionResult->isOk();
	
	}



	/**
	 *
	 * NAME:onSuccess
	 *
	 * DESCRIPTION: Callback executed when this form had success.DeleteAction
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
	 * DESCRIPTION: Callback executed when this action had an errorDeleteAction
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